import {
    TASKS_LOAD_SUCCESSFUL,
    TASKS_LOAD_IN_PROGRESS,
    TASKS_LOAD_FAILED,
    TASK_ACTION_FAILED,

    TASKS_TODO_LOAD_FAILED,
    TASKS_TODO_LOAD_SUCCESSFUL,
    TASKS_TODO_LOAD_IN_PROGRESS,

    TASKS_OVERDUE_LOAD_SUCCESSFUL,
    TASKS_OVERDUE_LOAD_FAILED,
    TASKS_OVERDUE_LOAD_IN_PROGRESS,

    TASKS_COMPLETED_LOAD_FAILED,
    TASKS_COMPLETED_LOAD_IN_PROGRESS,
    TASKS_COMPLETED_LOAD_SUCCESSFUL,
} from "./actions";

export default {
    TASKS_LOAD_IN_PROGRESS(state) {
        state.loadItemsError = null;
        state.loadingItems = true;
    },
    TASKS_LOAD_SUCCESSFUL(state, items) {
        state.items = items;
        state.loadingItems = false;
    },
    TASKS_LOAD_FAILED(state, err) {
        state.loadingItems = false;
        state.loadItemsError = err;
    },

    TASKS_COMPLETED_LOAD_IN_PROGRESS(state) {
        state.completedTasks.loadItemsError = null;
        state.completedTasks.loadingItems = true;
    },
    TASKS_COMPLETED_LOAD_SUCCESSFUL(state, items) {
        state.completedTasks.items = items;
        state.completedTasks.loadingItems = false;
    },
    TASKS_COMPLETED_LOAD_FAILED(state, err) {
        state.completedTasks.loadingItems = false;
        state.completedTasks.loadItemsError = err;
    },

    TASKS_TODO_LOAD_IN_PROGRESS(state) {
        state.todoTasks.loadItemsError = null;
        state.todoTasks.loadingItems = true;
    },
    TASKS_TODO_LOAD_SUCCESSFUL(state, items) {
        state.todoTasks.items = items;
        state.todoTasks.loadingItems = false;
    },
    TASKS_TODO_LOAD_FAILED(state, err) {
        state.todoTasks.loadingItems = false;
        state.todoTasks.loadItemsError = err;
    },

    TASKS_OVERDUE_LOAD_IN_PROGRESS(state) {
        state.overdueTasks.loadItemsError = null;
        state.overdueTasks.loadingItems = true;
    },
    TASKS_OVERDUE_LOAD_SUCCESSFUL(state, items) {
        state.overdueTasks.items = items;
        state.overdueTasks.loadingItems = false;
    },
    TASKS_OVERDUE_LOAD_FAILED(state, err) {
        state.overdueTasks.loadingItems = false;
        state.overdueTasks.loadItemsError = err;
    },

    TASK_ACTION_FAILED(state, err) {
        state.taskActionError = err;
    },
}
