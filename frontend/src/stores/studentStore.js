import { defineStore } from 'pinia';
import { fetchStudents, fetchClasses, fetchSections } from '@/api/studentApi';

export const useStudentStore = defineStore('studentStore', {
    state: () => ({
        students: [],
        pagination: {},
        loading: false,
        search: "",
        selectedClass: "",
        selectedSection: "",
        classes: [],
        sections: [],
    }),
    actions: {
        async loadStudents(page = 1) {
            this.loading = true;
            const params = { page };
            if (this.search) {
                params.search = this.search;
            }
            if (this.selectedClass) {
                params.class = this.selectedClass;
            }
            if (this.selectedSection) {
                params.section = this.selectedSection;
            }
            const response = await fetchStudents(params);
            this.students = response.data.data;
            this.pagination = {
                current_page: response.data.current_page,
                last_page: response.data.last_page,
            };

            this.loading = false;

            },
        async loadClasses() {
            const response = await fetchClasses();
            this.classes = response.data.classes;
        },
        async loadSections(className) {
            if (className) {
                const response = await fetchSections(className);
                this.sections = response.data.sections;
            } else {
                this.sections = [];
            }
        },
        resetFilters() {
            this.search = "";
            this.selectedClass = "";
            this.selectedSection = "";
            this.loadStudents();
        }
    }
});
