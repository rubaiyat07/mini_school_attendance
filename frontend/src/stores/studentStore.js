import { defineStore } from 'pinia';
import { fetchStudents } from '@/api/studentApi';

export const useStudentStore = defineStore('studentStore', {
    state: () => ({
        students: [],
        pagination: {},
        loading: false,
        search: "",
    }),
    actions: {
        async loadStudents(page = 1) {
            this.loading = true;
            const params = { page };
            if (this.search) {
                params.search = this.search;
            }
            const response = await fetchStudents(params);
            this.students = response.data.data;
            this.pagination = {
                current_page: response.data.current_page,
                last_page: response.data.last_page,
            };

            this.loading = false;

            }
    }
});