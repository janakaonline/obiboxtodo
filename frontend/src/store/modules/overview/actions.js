import {getCompletionOverview} from "../../../api/overview";

export const OVERVIEW_COMPLETION_LOAD_IN_PROGRESS = 'OVERVIEW_COMPLETION_LOAD_IN_PROGRESS';
export const OVERVIEW_COMPLETION_LOAD_SUCCESSFUL = 'OVERVIEW_COMPLETION_LOAD_SUCCESSFUL';
export const OVERVIEW_COMPLETION_LOAD_FAILED = 'OVERVIEW_COMPLETION_LOAD_FAILED';

export default {
    async loadCompletionOverview({commit}) {
        try {
            commit(OVERVIEW_COMPLETION_LOAD_IN_PROGRESS);
            const tasks = await getCompletionOverview();
            commit(OVERVIEW_COMPLETION_LOAD_SUCCESSFUL, tasks);
        } catch (err) {
            console.log(err);
            commit(OVERVIEW_COMPLETION_LOAD_FAILED, err);
        }
    },
}
