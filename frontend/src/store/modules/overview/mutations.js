import {
    OVERVIEW_COMPLETION_LOAD_IN_PROGRESS,
    OVERVIEW_COMPLETION_LOAD_SUCCESSFUL,
    OVERVIEW_COMPLETION_LOAD_FAILED,
    OVERVIEW_TASKS_BY_PRIORITY_LOAD_FAILED,
    OVERVIEW_TASKS_BY_PRIORITY_LOAD_IN_PROGRESS,
    OVERVIEW_TASKS_BY_PRIORITY_LOAD_SUCCESSFUL,
} from "./actions";

export default {
    OVERVIEW_COMPLETION_LOAD_IN_PROGRESS(state) {
        state.taskCompletionData.error = null;
        state.taskCompletionData.loading = true;
    },
    OVERVIEW_COMPLETION_LOAD_SUCCESSFUL(state, data) {
        state.taskCompletionData.data = data;
        state.taskCompletionData.loading = false;
    },
    OVERVIEW_COMPLETION_LOAD_FAILED(state, err) {
        state.taskCompletionData.error = err;
        state.taskCompletionData.loading = false;
    },

    OVERVIEW_TASKS_BY_PRIORITY_LOAD_IN_PROGRESS(state) {
        state.tasksByPriorityData.error = null;
        state.tasksByPriorityData.loading = true;
    },
    OVERVIEW_TASKS_BY_PRIORITY_LOAD_SUCCESSFUL(state, data) {
        state.tasksByPriorityData.data = data;
        state.tasksByPriorityData.loading = false;
    },
    OVERVIEW_TASKS_BY_PRIORITY_LOAD_FAILED(state, err) {
        state.tasksByPriorityData.error = err;
        state.tasksByPriorityData.loading = false;
    },
}

