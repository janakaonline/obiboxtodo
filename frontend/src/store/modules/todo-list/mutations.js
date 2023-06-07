import {
    TASKS_LOAD_SUCCESSFUL,
    TASKS_LOAD_IN_PROGRESS,
    TASKS_LOAD_FAILED,
    TASK_ACTION_FAILED,
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
    TASK_ACTION_FAILED(state, err) {
        state.taskActionError = err;
    },
}
