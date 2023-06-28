<template>
    <div id="list-view">
        <header>
            <div class="d-flex justify-content-end">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Menu
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Share</a></li>
                    </ul>
                </div>
                <button class="btn btn-primary ms-2" @click="refreshData">Refresh</button>
                <button class="btn btn-primary ms-2" @click="createNew">New</button>
            </div>
        </header>

        <main>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th v-for="column in columns" :key="column">{{ column }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in rows" :key="row.name">
                            <td v-for="column in columns" :key="column">{{ row[column] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>

        <footer>
            <nav class="mt-3" aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ active: pageSize === 20 }" @click="changePageSize(20)">
                        <a class="page-link" href="#">20</a>
                    </li>
                    <li class="page-item" :class="{ active: pageSize === 50 }" @click="changePageSize(50)">
                        <a class="page-link" href="#">50</a>
                    </li>
                    <li class="page-item" :class="{ active: pageSize === 100 }" @click="changePageSize(100)">
                        <a class="page-link" href="#">100</a>
                    </li>
                </ul>
            </nav>
        </footer>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "ListView",
    props: ['tableName'],
    data() {
        return {
            columns: [],
            rows: [],
            pageSize: 20,
        }
    },
    created() {
        console.log('ListView.created.tableName', this.tableName);
        this.fetchData();
    },
    methods: {
        fetchData() {
            // Make an API call to fetch the data from the specified table
            axios.get(`/api/fetchData.php?table=${this.tableName}`)
                .then((response) => {
                    this.columns = response.data.columns;
                    this.rows = response.data.rows;
                })
                .catch((error) => {
                    console.error('Error fetching data: ', error);
                })
        },
        refreshData() {
            // Refresh the data by making another API call
            this.fetchData();
        },
        createNew() {
            // Handle creating a new record
        },
        changePageSize(size) {
            // Change page size and fetch additional rows
            this.pageSize = size;
            // Make an API call to fetch additional rows based on the new page size
            // Update the rows data with the fetched rows
        },
    },
    watch: {
      tableName(newId) {
        console.log('newId', newId);
      }
    }
};
</script>

<style scoped>
</style>