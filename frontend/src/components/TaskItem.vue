<template>
    <div>
        <v-list-item>
            <v-list-item-title :class="[{'text-decoration-line-through': item.completed}]">
                {{ item.name }}
            </v-list-item-title>
            <v-list-item-subtitle :class="[{'text-decoration-line-through': item.completed}]">
                {{ item.description }}
            </v-list-item-subtitle>
            <template v-slot:prepend>
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
        </v-list-item>

        <v-dialog v-model="editMode" width="auto">
            <v-sheet width="350" class="mx-auto" :color="editTaskMoodColor">
                <v-form @submit.prevent>

                    <v-text-field
                        v-model="editableItem.name"
                        label="Task Name"
                    ></v-text-field>

                    <v-textarea
                        name="description"
                        variant="filled"
                        label="Description"
                        auto-grow
                        v-model="editableItem.description"
                    ></v-textarea>


                    <v-radio-group v-model="editableItem.priority" inline>
                        <template v-slot:label>
                            <div class="text-caption">Priority</div>
                        </template>
                        <v-radio
                            label="High"
                            color="red"
                            value="high"
                        ></v-radio>
                        <v-radio
                            label="Medium"
                            color="orange"
                            value="medium"
                        ></v-radio>
                        <v-radio
                            label="Low"
                            color="grey"
                            value="low"
                        ></v-radio>
                    </v-radio-group>

                    <div class="pa-2 d-flex ">
                        <VueDatePicker class="justify-center" v-model="editableItem.due_date" inline auto-apply
                                       :enable-time-picker="false"></VueDatePicker>
                    </div>


                    <div class="ma-3 justify-space-between d-flex">
                        <v-btn color="primary" type="submit" @click.prevent="edit">Save</v-btn>
                        <v-btn color="grey" class="ml-8" @click.prevent="closeEditDialog">Cancel</v-btn>
                    </div>
                </v-form>
            </v-sheet>
        </v-dialog>

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
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    name: "TaskItem",
    components: {
        VueDatePicker
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
        }
    },
    methods: {
        openEditDialog() {
            this.editableItem = {...this.item};
            this.editMode = true;

        },
        closeEditDialog() {
            this.editMode = false;
        },
        edit() {
            this.$store.dispatch('todoList/edit', this.editableItem)
                .then(() => {
                    this.editMode = false;
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

<style scoped>

</style>
