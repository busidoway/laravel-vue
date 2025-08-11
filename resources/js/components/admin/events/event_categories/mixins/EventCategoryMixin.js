export default {
    data() {
        return {
            dataSubCategories: [],
            dataSubCatJoinedIds: []
        };
    },
    methods: {
        getSubCategories(data) {
            if (data) this.dataSubCategories = data
        },
        returnBack() {
            this.$router.push({ name: 'EventCategories' });
        },
        errorMessage(error, message) {
            this.response.status = 'error';
            if (error.response && error.response.data && error.response.data.errors) {
                this.errors.push(error.response.data.errors.name[0]);
            } else {
                this.errors.push(message);
            }
        },
        errorResponseMessage(error) {
            if (error) {
                const messages = error;

                if (typeof messages === 'object' && !Array.isArray(messages)) {
                    // Если это объект с полями (например, title: [..])
                    Object.values(messages).forEach(group => {
                        if (Array.isArray(group)) {
                            group.forEach(msg => this.errors.push(msg));
                        } else if (typeof group === 'string') {
                            this.errors.push(group);
                        }
                    });
                } else if (typeof messages === 'string') {
                    // Если это просто строка
                    this.errors.push(messages);
                }
            }
        }
    }
}
