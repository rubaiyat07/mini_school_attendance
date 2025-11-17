import axios from 'axios';

//Students Attendance
export const fetchStudnets = (page = 1, search = "") => {
    return axios.get(`/students/page=${page}&search=${search}`);
};

// Single Student Attendance
export const recordAttendance = (data) => {
    return axios.post("/attendance", data);
};

//Record Bulk Attendance
export const resordBulkAttendance = (data) => {
    return axios.post("/attendance/bulk", data);
};

//Dashboard Attendance Stats
export const fetchAttendanceStats = () => {
    return axios.get("/attendance/stats");
};

//Dashboard Data
export const fetchDashboardData = () => {
    return axios.get("/attendance/dashboard");
};
