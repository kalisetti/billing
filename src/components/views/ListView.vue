<template>
    <div id="list-view">
        <header>
            <div class="d-flex justify-content-end">
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Menu
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Share</a></li>
                    </ul>
                </div>
                <button class="btn btn-sm btn-primary ms-2" @click="refreshData">Refresh</button>
                <button class="btn btn-sm btn-primary ms-2" @click="createNewRecord">New</button>
            </div>
        </header>

        <main>
            <div v-if="isLoading" class="loading-overlay">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <caption>List view results for table {{ tableName }}</caption>
                    <thead>
                        <tr>
                            <th v-for="column in columns" :key="column">{{ column }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="gridRow" v-for="row in rows" :key="row.name" @click="editRecord(row)">
                            <td v-for="column in columns" :key="column">{{ row[column] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>

        <footer>
            <nav class="mt-3" aria-label="Page navigation">
                <div class="d-flex justify-content-start">
                    <ul class="pagination">
                        <li class="page-item" :class="{ active: pageSize === 20 }" @click="changePageSize(20)">
                            <!-- <a class="page-link" href="#">5</a> -->
                            <span class="page-link">20</span>
                        </li>
                        <li class="page-item" :class="{ active: pageSize === 100 }" @click="changePageSize(100)">
                            <!-- <a class="page-link" href="#">10</a> -->
                            <span class="page-link">100</span>
                        </li>
                        <li class="page-item" :class="{ active: pageSize === 200 }" @click="changePageSize(200)">
                            <!-- <a class="page-link" href="#">15</a> -->
                            <span class="page-link">200</span>
                        </li>
                    </ul>
                    <ul class="pagination ms-auto">
                        <li class="page-item" @click="loadMore">
                            <span class="page-link">More</span>
                        </li>
                    </ul>
                </div>
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
            offset: 0,
            isLoading: false,
        }
    },
    created() {
        console.log('ListView.created.tableName', this.tableName);
        this.fetchData();
    },
    methods: {
        fetchData() {
            this.isLoading = true;
            console.log(`ListView.fetchDate: limit ${this.pageSize} offset ${this.offset}`);
            // Make an API call to fetch the data from the specified table
            axios.get(`/api/fetchData.php?table=${this.tableName}&limit=${this.pageSize}&offset=${this.offset}`)
                .then((response) => {
                    this.columns = response.data.columns;
                    this.rows.push.apply(this.rows, response.data.rows);
                })
                .catch((error) => {
                    console.error('Error fetching data: ', error);
                })
                .finally(() => {
                    this.isLoading = false;
                })
        },
        refreshData() {
            // Refresh the data by making another API call
            if (this.offset) {
                this.pageSize += this.offset;
                this.offset = 0;
            }
            this.fetchData();
        },
        createNewRecord() {
            // Handle creating a new record
            const newRecordId = `new-${this.tableName}`;
            this.$router.push(`/desk/Edit/${this.tableName}/${newRecordId}`);
        },
        editRecord(row) {
            const recordId = row.name;
            this.$router.push(`/desk/Edit/${this.tableName}/${recordId}`);
        },
        changePageSize(size) {
            console.log('changePagesize: ', size);
            this.offset = 0;
            this.pageSize = size;
            this.fetchData();
        },
        loadMore() {
            this.offset += this.pageSize;
            this.fetchData();
        }
    },
    watch: {
      tableName(newId) {
        console.log('ListView.watch.newId: ', newId);
        this.rows = [];
        this.columns = [];
        this.offset = 0;
        this.fetchData();
      }
    }
};
</script>

<style scoped>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

.loading-overlay .spinner-border {
    color: #ffffff;
}

.page-link {
    cursor: pointer;
}

header {
    margin-bottom: 1rem;
}

table {
    white-space: nowrap;
}

thead {
    background-color: #b0c4de;
}

.gridRow:hover {
    cursor: pointer;
    background-color:darkkhaki;
}
</style>