<script setup>
import {ref, reactive, onMounted, watch} from 'vue'
import {del, getWithParams, post} from "../utils/fetchAPI.js";
import Validation from "../components/Validation.vue";
import {toast} from 'vue3-toastify';
import Pagination from "../components/Pagination.vue";

// Filter state
const filterOptions = [
    {label: 'All', value: 'all'},
    {label: 'Active', value: 0},
    {label: 'Completed', value: 1},
]
const newTask = reactive({
    title: '',
    body: ''
})
const editTask = reactive({
    title: '',
    body: '',
    _method: 'PUT',
})
let errors = reactive([])
const tasks = ref([])
const currentFilter = ref('all')
const nextCursor = ref(null)
const prevCursor = ref(null)
const isLoading = ref(false)

const getError = (key) => {
    return !!errors && errors[key] !== undefined ? errors[key][0] : null;
}

const addTask = () => {
    handleFormSubmit({formData: newTask})
}
const toggleTask = (task) => {
    handleFormSubmit({operation: "edit", formData: {status: !task.status, _method: 'PATCH'}, task: task})
}
const removeTask = (id) => {
    const isConfirmed = confirm('Are you sure to delete?')
    if (isConfirmed) {
        del(`tasks/${id}`)
            .then(res => {
                if (res?.data?.success) {
                    tasks.value = tasks.value.filter(Task => Task.id !== id)
                    toast(res?.data?.message || 'Task deleted successfully', {
                        autoClose: 1000,
                    });
                }
                if (tasks.value.length === 0) fetchTask()
            })
    }
}

// Toggle expanded state
const toggleExpand = (task) => {
    task.expanded = !task.expanded
}

// Edit functionality
const startEditing = (task) => {
    // First, cancel any other editing
    tasks.value.forEach(t => {
        if (t.id !== task.id) {
            t.isEditing = false
        }
    })
    // Set the current Task to edit mode
    task.isEditing = true
    editTask.title = task.title
    editTask.body = task.body
}

const saveEdit = (task) => {
    handleFormSubmit({operation: "edit", formData: editTask, task: task})
}

const cancelEdit = (task) => {
    task.isEditing = false
}

const handleFormSubmit = async ({operation = "add", formData, task = null}) => {
    isLoading.value = true
    let url = operation === "edit" ? `/tasks/${task?.id}` : "/tasks";
    if (operation === "edit" && !task?.id) return console.log("Id not found");

    await post(url, formData)
        .then((res) => {
            if (res?.data?.success) {
                const {data} = res?.data
                if (operation === "edit" && Object.keys(task).length > 0) {
                    task.title = data?.title
                    task.body = data?.body
                    task.status = data?.status
                    task.isEditing = false
                } else {
                    tasks.value.unshift({
                        ...data,
                        isEditing: false,
                        expanded: false
                    })
                }

                formData.title = ''
                formData.body = ''
                errors = []
                toast(res?.data?.message || 'Task saved successfully', {
                    autoClose: 1000,
                });
            }
        }).catch(err => {
            errors = err?.response?.data?.errors;
        })
        .finally(_ => {
            isLoading.value = false
        })

}

const fetchTask = async (cursor = null) => {
    const params = {
        limit: 5,
        ...(cursor && {cursor}),
        ...(currentFilter.value !== 'all' && {status: currentFilter.value}),
    };

    getWithParams('/tasks', params)
        .then(res => {
            if (res?.data?.success) {
                const {data, next_cursor, prev_cursor} = res?.data?.data
                tasks.value = data.map(task => ({
                    ...task,
                    isEditing: false,
                    expanded: false
                }))
                nextCursor.value = next_cursor
                prevCursor.value = prev_cursor
            }
        })
};

watch(currentFilter, () => {
    fetchTask();
})

onMounted(() => {
    fetchTask();
});


</script>
<template>
    <div class="bg-gray-100 py-8 min-h-[calc(100vh-64px)]">
        <div class="mx-auto max-w-md px-4 sm:px-6">
            <div class="rounded-xl bg-white p-6 shadow-md">
                <!-- Header -->
                <div class="mb-6 text-center">
                    <h1 class="text-2xl font-bold text-gray-800">Task List</h1>
                    <p class="text-sm text-gray-500">Keep track of your tasks</p>
                </div>

                <form @submit.prevent="addTask" class="mb-6">
                    <div class="space-y-3">
                        <input
                            v-model="newTask.title"
                            type="text"
                            placeholder="Task title..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-1 mb-0 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                        />
                        <Validation :error-text="getError('title')"/>
                        <div>
              <textarea
                  v-model="newTask.body"
                  placeholder="Task body"
                  class="w-full rounded-lg border border-gray-300 px-4 py-1 mt-3 mb-0 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
              ></textarea>
                            <Validation :error-text="getError('body')"/>
                        </div>
                        <button
                            :disabled="isLoading"
                            type="submit"
                            class="w-full rounded-lg bg-emerald-500 px-4 py-1 font-medium text-white transition-colors hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 cursor-pointer"
                        >
                            Add Task
                        </button>
                    </div>
                </form>

                <!-- Filters -->
                <div class="mb-4 flex justify-center space-x-2">
                    <button
                        v-for="option in filterOptions"
                        :key="option.value"
                        @click="currentFilter = option.value"
                        :class="[
              'rounded-md px-3 py-1 text-sm font-medium transition-colors cursor-pointer',
              currentFilter === option.value
                ? 'bg-emerald-100 text-emerald-800'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
                    >
                        {{ option.label }}
                    </button>
                </div>

                <!-- Task List -->
                <div v-if="tasks.length > 0" class="mb-4 space-y-3">
                    <div
                        v-for="task in tasks"
                        :key="task.id"
                        class="overflow-hidden rounded-lg border border-gray-200 transition-colors"
                        :class="{ 'bg-gray-50': task.status }"
                    >
                        <!-- View Mode -->
                        <div v-if="!task.isEditing">
                            <!-- Task Header -->
                            <div class="flex items-center justify-between px-4 py-3">
                                <div class="flex flex-1 items-center">
                                    <input
                                        type="checkbox"
                                        :checked="task.status"
                                        @change="toggleTask(task)"
                                        class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                                    />
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center">
                      <span
                          class="font-medium text-gray-800"
                          :class="{ 'line-through text-gray-500': task.status }"
                      >
                        {{ task.title }}
                      </span>
                                            <button
                                                v-if="task.body"
                                                @click="toggleExpand(task)"
                                                class="ml-2 rounded-full p-1 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 cursor-pointer"
                                                :title="task.expanded ? 'Collapse details' : 'Show details'"
                                            >
                                                <svg v-if="!task.expanded" xmlns="http://www.w3.org/2000/svg" width="16"
                                                     height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="lucide lucide-chevron-down">
                                                    <path d="m6 9 6 6 6-6"/>
                                                </svg>
                                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="lucide lucide-chevron-up">
                                                    <path d="m18 15-6-6-6 6"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        @click="startEditing(task)"
                                        class="rounded-md p-1 text-gray-400 transition-colors hover:bg-blue-100 hover:text-blue-500 cursor-pointer"
                                        title="Edit"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="lucide lucide-pencil">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                    </button>
                                    <button
                                        @click="removeTask(task.id)"
                                        class="rounded-md p-1 text-gray-400 transition-colors hover:bg-red-100 hover:text-red-500 cursor-pointer"
                                        title="Delete"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="lucide lucide-trash-2">
                                            <path d="M3 6h18"/>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                            <line x1="10" x2="10" y1="11" y2="17"/>
                                            <line x1="14" x2="14" y1="11" y2="17"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Task Body (Expandable) -->
                            <div
                                v-if="task.body && task.expanded"
                                class="border-t border-gray-200 bg-white px-4 py-3"
                                :class="{ 'bg-gray-50': task.status }"
                            >
                                <p class="whitespace-pre-wrap text-sm text-gray-600"
                                   :class="{ 'text-gray-400': task.status }">
                                    {{ task.body }}
                                </p>
                            </div>
                        </div>

                        <!-- Edit Mode -->
                        <div v-else class="p-4">
                            <div class="space-y-3">
                                <div>
                                    <input
                                        v-model="editTask.title"
                                        type="text"
                                        class="w-full rounded-md border border-gray-300 px-3 py-1 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    />
                                </div>
                                <div>
                  <textarea
                      v-model="editTask.body"
                      class="w-full rounded-md border border-gray-300 px-3 py-1 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                  ></textarea>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button
                                        @click="cancelEdit(task)"
                                        class="rounded-md bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        :disabled="isLoading"
                                        @click="saveEdit(task)"
                                        class="rounded-md bg-emerald-500 px-3 py-1 text-sm font-medium text-white transition-colors hover:bg-emerald-600"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-else
                    class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 py-8"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-list text-gray-400">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="M12 11h4"/>
                        <path d="M12 16h4"/>
                        <path d="M8 11h.01"/>
                        <path d="M8 16h.01"/>
                    </svg>
                    <p class="mt-2 text-gray-500">
                        {{
                            currentFilter === 'all'
                                ? 'No tasks yet. Add one above!'
                                : currentFilter === 0
                                    ? 'No active tasks!'
                                    : 'No completed tasks!'
                        }}
                    </p>
                </div>

                <Pagination
                    v-if="tasks.length > 0"
                    :prev-cursor="prevCursor"
                    :next-cursor="nextCursor"
                    @paginate="fetchTask"
                />
            </div>
        </div>
    </div>
</template>
