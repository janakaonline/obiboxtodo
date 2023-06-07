<template>
    <div>

        <v-list-item >

            <v-list-item-title :class="[{'text-decoration-line-through': item.completed}]">
                {{ item.name }}
            </v-list-item-title>
            <v-list-item-subtitle :class="[{'text-decoration-line-through': item.completed}]">
                {{ item.description }}
            </v-list-item-subtitle>
            <template v-slot:prepend>
                <v-icon class="priority-icon" :icon="taskPriorityIcon" :color="taskMoodColor"></v-icon>

                <v-btn v-if="!item.completed"
                       class="ma-1"
                       density="comfortable"
                       color="success"
                       icon="mdi-check-circle-outline"
                       @click.prevent="markAsComplete"
                ></v-btn>
                <v-btn v-if="item.completed"
                       class="ma-1"
                       density="comfortable"
                       color="grey"
                       icon="mdi-restore"
                       @click.prevent="markAsInComplete"
                ></v-btn>
            </template>
            <template v-slot:append>
                <v-btn class="ma-1"
                       density="compact"
                       color="grey"
                       variant="text"
                       icon="mdi-pencil"
                       @click.prevent="openEditDialog"
                ></v-btn>
                <v-btn class="ma-1"
                       density="compact"
                       color="red"
                       variant="text"
                       icon="mdi-trash-can"
                       @click.prevent="confirmDeletion = true"
                ></v-btn>
            </template>
            <div class="item-status-bar text-grey-darken-1 text-sm-body-2 text-right">Due by {{formattedDueDate}}</div>
        </v-list-item>

        <task-save-dialog @submit="edit" ref="editDialog"></task-save-dialog>

        <v-dialog v-model="confirmDeletion" width="auto">
            <v-card>
                <v-card-text>
                    <strong>Are you sure you want to delete task {{ item.name }}?</strong>
                </v-card-text>
                <v-card-actions class="justify-center">
                    <v-btn color="red" variant="flat" @click="remove(item.id)">Yes</v-btn>
                    <v-btn color="grey" variant="flat" @click="confirmDeletion = false">No</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import TaskSaveDialog from "./TaskSaveDialog.vue";
import moment from 'moment';

export default {
    name: "TaskItem",
    components: {
        TaskSaveDialog
    },
    props: {
        item: Object,
    },
    data() {
        return {
            confirmDeletion: false,
            editMode: false,
            editableItem: {},
        }
    },
    computed: {
        editTaskMoodColor() {
            let color = 'orange-lighten-5';
            switch (this.editableItem.priority) {
                case 'high':
                    color = 'red-lighten-5';
                    break;
                case 'low':
                    color = 'grey-lighten-5';
                    break;
            }
            return color;
        },
        taskMoodColor() {
            let color = 'orange';
            switch (this.item.priority) {
                case 'high':
                    color = 'red';
                    break;
                case 'low':
                    color = 'grey';
                    break;
            }
            return color;
        },
        taskPriorityIcon() {
            let icon = 'mdi-minus-circle';
            switch (this.item.priority) {
                case 'high':
                    icon = 'mdi-arrow-up-circle';
                    break;
                case 'low':
                    icon = 'mdi-arrow-down-circle';
                    break;
            }
            return icon;
        },
        formattedDueDate(){
            return moment(this.item.due_date).format('Do MMM, YYYY');
        },
    },
    methods: {
        openEditDialog() {
            this.$refs.editDialog.open(this.item);
        },
        edit(editableItem) {
            this.$store.dispatch('todoList/edit', editableItem)
                .then(() => {
                    this.$refs.editDialog.close();
                    this.$emit('updated');
                })
        },
        remove() {
            this.$store.dispatch('todoList/remove', this.item.id)
                .then(() => {
                    this.confirmDeletion = false;
                    this.$emit('updated');
                })
        },
        markAsComplete() {
            this.$store.dispatch('todoList/markAsComplete', this.item.id)
                .then(() => {
                    this.$emit('updated');
                })
        },
        markAsInComplete() {
            this.$store.dispatch('todoList/markAsInComplete', this.item.id)
                .then(() => {
                    this.$emit('updated');
                })
        }
    }
}
</script>

<style scoped lang="sass">
.priority-icon
    position: absolute
    margin-inline-end: 0
    z-index: 2
    top:5px
    left:10px
</style>
