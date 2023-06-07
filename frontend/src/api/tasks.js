import axios from "axios";

export const getTasks = async (queryString) => {
    const resp = await axios.get(`http://localhost:8001/api/tasks?${queryString}`);
    return resp.data.items;
}

export const getTodoTasks = async (queryString) => {
    const resp = await axios.get(`http://localhost:8001/api/tasks/due-today?${queryString}`);
    return resp.data.items;
}

export const getOverdueTasks = async (queryString) => {
    const resp = await axios.get(`http://localhost:8001/api/tasks/overdue?${queryString}`);
    return resp.data.items;
}

export const addTask = async (task) => {
    const resp = await axios.post(`http://localhost:8001/api/tasks`, task);
    return resp.data;
}

export const updateTask = async (task) => {
    const resp = await axios.put(`http://localhost:8001/api/tasks/${task.id}`, task);
    return resp.data;
}

export const removeTask = async (id) => {
    const resp = await axios.delete(`http://localhost:8001/api/tasks/${id}`);
    return resp.data;
}

export const markTaskAsComplete = async (id) => {
    const resp = await axios.patch(`http://localhost:8001/api/tasks/${id}/complete`);
    return resp.data;
}

export const markTaskAsInComplete = async (id) => {
    const resp = await axios.patch(`http://localhost:8001/api/tasks/${id}/incomplete`);
    return resp.data;
}
