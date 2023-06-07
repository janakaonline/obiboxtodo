import {
    getTasks,
    markTaskAsComplete,
    markTaskAsInComplete,
    removeTask,
    updateTask,
    addTask,
    getTodoTasks,
    getOverdueTasks
} from "../../../api/tasks";

export const TASKS_LOAD_IN_PROGRESS = 'TASKS_LOAD_IN_PROGRESS'
export const TASKS_LOAD_FAILED = 'TASKS_LOAD_FAILED'
export const TASKS_LOAD_SUCCESSFUL = 'TASKS_LOAD_SUCCESSFUL'

export const TASKS_COMPLETED_LOAD_IN_PROGRESS = 'TASKS_COMPLETED_LOAD_IN_PROGRESS'
export const TASKS_COMPLETED_LOAD_FAILED = 'TASKS_COMPLETED_LOAD_FAILED'
export const TASKS_COMPLETED_LOAD_SUCCESSFUL = 'TASKS_COMPLETED_LOAD_SUCCESSFUL'

export const TASKS_TODO_LOAD_IN_PROGRESS = 'TASKS_TODO_LOAD_IN_PROGRESS'
export const TASKS_TODO_LOAD_FAILED = 'TASKS_TODO_LOAD_FAILED'
export const TASKS_TODO_LOAD_SUCCESSFUL = 'TASKS_TODO_LOAD_SUCCESSFUL'

export const TASKS_OVERDUE_LOAD_IN_PROGRESS = 'TASKS_OVERDUE_LOAD_IN_PROGRESS'
export const TASKS_OVERDUE_LOAD_FAILED = 'TASKS_OVERDUE_LOAD_FAILED'
export const TASKS_OVERDUE_LOAD_SUCCESSFUL = 'TASKS_OVERDUE_LOAD_SUCCESSFUL'

export const TASK_ACTION_FAILED = 'TASK_ACTION_FAILED'

export default {
    async loadTasks({commit}, options) {
        try {
            commit(TASKS_LOAD_IN_PROGRESS);
            let optionArr = [];

            //compile sort options
            if (options.sort && Array.isArray(options.sort)) {
                options.sort.forEach((sortItem) => {
                    if (sortItem.order) {
                        optionArr.push(`sort_${sortItem.field}=${sortItem.order}`);
                    }
                });
            }

            //compile filter options
            if (options.filter && Array.isArray(options.filter)) {
                options.filter.forEach((filterItem) => {
                    if (filterItem.value) {
                        optionArr.push(`filter_${filterItem.field}=${filterItem.value}`);
                    }
                });
            }

            let queryString = optionArr.join('&');
            const tasks = await getTasks(queryString);
            commit(TASKS_LOAD_SUCCESSFUL, tasks);
        } catch (err) {
            console.log(err);
            commit(TASKS_LOAD_FAILED, err);
        }
    },

    async loadCompletedTasks({commit}, options) {
        try {
            commit(TASKS_COMPLETED_LOAD_IN_PROGRESS);
            let optionArr = [];

            //compile sort options
            if (options.sort && Array.isArray(options.sort)) {
                options.sort.forEach((sortItem) => {
                    if (sortItem.order) {
                        optionArr.push(`sort_${sortItem.field}=${sortItem.order}`);
                    }
                });
            }

            //compile filter options
            if (options.filter && Array.isArray(options.filter)) {
                options.filter.forEach((filterItem) => {
                    if (filterItem.value) {
                        optionArr.push(`filter_${filterItem.field}=${filterItem.value}`);
                    }
                });
            }

            let queryString = optionArr.join('&');
            const tasks = await getTasks(queryString);
            commit(TASKS_COMPLETED_LOAD_SUCCESSFUL, tasks);
        } catch (err) {
            console.log(err);
            commit(TASKS_COMPLETED_LOAD_FAILED, err);
        }
    },

    async loadTodoTasks({commit}, options) {
        try {
            commit(TASKS_TODO_LOAD_IN_PROGRESS);
            let optionArr = [];

            //compile sort options
            if (options.sort && Array.isArray(options.sort)) {
                options.sort.forEach((sortItem) => {
                    if (sortItem.order) {
                        optionArr.push(`sort_${sortItem.field}=${sortItem.order}`);
                    }
                });
            }

            let queryString = optionArr.join('&');
            const tasks = await getTodoTasks(queryString);
            commit(TASKS_TODO_LOAD_SUCCESSFUL, tasks);
        } catch (err) {
            console.log(err);
            commit(TASKS_TODO_LOAD_FAILED, err);
        }
    },

    async loadOverdueTasks({commit}, options) {
        try {
            commit(TASKS_OVERDUE_LOAD_IN_PROGRESS);
            let optionArr = [];

            //compile sort options
            if (options.sort && Array.isArray(options.sort)) {
                options.sort.forEach((sortItem) => {
                    if (sortItem.order) {
                        optionArr.push(`sort_${sortItem.field}=${sortItem.order}`);
                    }
                });
            }

            let queryString = optionArr.join('&');
            const tasks = await getOverdueTasks(queryString);
            commit(TASKS_OVERDUE_LOAD_SUCCESSFUL, tasks);
        } catch (err) {
            console.log(err);
            commit(TASKS_OVERDUE_LOAD_FAILED, err);
        }
    },

    async add({commit}, taskData) {
        try {
            await addTask(taskData);
        } catch (err) {
            console.log(err);
            commit(TASK_ACTION_FAILED, err);
        }
    },

    async edit({commit}, task) {
        try {
            await updateTask(task);
        } catch (err) {
            console.log(err);
            commit(TASK_ACTION_FAILED, err);
        }
    },

    async remove({commit}, taskId) {
        try {
            await removeTask(taskId);
        } catch (err) {
            console.log(err);
            commit(TASK_ACTION_FAILED, err);
        }
    },

    async markAsComplete({commit}, taskId) {
        try {
            await markTaskAsComplete(taskId);
        } catch (err) {
            console.log(err);
            commit(TASK_ACTION_FAILED, err);
        }
    },

    async markAsInComplete({commit}, taskId) {
        try {
            await markTaskAsInComplete(taskId);
        } catch (err) {
            console.log(err);
            commit(TASK_ACTION_FAILED, err);
        }
    }
}
