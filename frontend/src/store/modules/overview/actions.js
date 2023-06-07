import {getCompletionOverview, getTasksByPriorityOverview} from "../../../api/overview";

export const OVERVIEW_COMPLETION_LOAD_IN_PROGRESS = 'OVERVIEW_COMPLETION_LOAD_IN_PROGRESS';
export const OVERVIEW_COMPLETION_LOAD_SUCCESSFUL = 'OVERVIEW_COMPLETION_LOAD_SUCCESSFUL';
export const OVERVIEW_COMPLETION_LOAD_FAILED = 'OVERVIEW_COMPLETION_LOAD_FAILED';

export const OVERVIEW_TASKS_BY_PRIORITY_LOAD_IN_PROGRESS = 'OVERVIEW_TASKS_BY_PRIORITY_LOAD_IN_PROGRESS';
export const OVERVIEW_TASKS_BY_PRIORITY_LOAD_SUCCESSFUL = 'OVERVIEW_TASKS_BY_PRIORITY_LOAD_SUCCESSFUL';
export const OVERVIEW_TASKS_BY_PRIORITY_LOAD_FAILED = 'OVERVIEW_TASKS_BY_PRIORITY_LOAD_FAILED';

export default {
    async loadCompletionOverview({commit}) {
        try {
            commit(OVERVIEW_COMPLETION_LOAD_IN_PROGRESS);
            const res = await getCompletionOverview();
            commit(OVERVIEW_COMPLETION_LOAD_SUCCESSFUL, res.data);
        } catch (err) {
            console.log(err);
            commit(OVERVIEW_COMPLETION_LOAD_FAILED, err);
        }
    },

    async loadTasksByPriorityOverview({commit}) {
        try {
            commit(OVERVIEW_TASKS_BY_PRIORITY_LOAD_IN_PROGRESS);
            const res = await getTasksByPriorityOverview();
            commit(OVERVIEW_TASKS_BY_PRIORITY_LOAD_SUCCESSFUL, res.data);
        } catch (err) {
            console.log(err);
            commit(OVERVIEW_TASKS_BY_PRIORITY_LOAD_FAILED, err);
        }
    },
}
