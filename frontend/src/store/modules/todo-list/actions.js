import {getTasks, markTaskAsComplete, markTaskAsInComplete, removeTask, updateTask} from "../../../api/tasks";

export const TASKS_LOAD_IN_PROGRESS = 'TASKS_LOAD_IN_PROGRESS'
export const TASKS_LOAD_FAILED = 'LOAD_TASKS_FAILED'
export const TASKS_LOAD_SUCCESSFUL = 'TASKS_LOAD_SUCCESSFUL'

export const TASK_ACTION_FAILED = 'TASK_ACTION_FAILED'

export default {
    async loadTasks({commit}) {
        try {
            commit(TASKS_LOAD_IN_PROGRESS);
            const tasks = await getTasks();
            commit(TASKS_LOAD_SUCCESSFUL, tasks);
        } catch (err) {
            console.log(err);
            commit(TASKS_LOAD_FAILED, err);
        }
    },

    async add({commit}, taskData) {
        try {
            await markTaskAsInComplete(taskId);
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
