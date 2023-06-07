import axios from "axios";

export const getCompletionOverview = async () => {
    const resp = await axios.get(`http://localhost:8001/api/overview/task-completion`);
    return resp.data;
}

export const getTasksByPriorityOverview = async () => {
    const resp = await axios.get(`http://localhost:8001/api/overview/tasks-by-priority`);
    return resp.data;
}
