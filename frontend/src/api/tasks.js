import axios from "axios";

export const getTasks = async () => {
    const resp = await axios.get('http://localhost:8001/api/tasks');
    return resp.data.items;
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
