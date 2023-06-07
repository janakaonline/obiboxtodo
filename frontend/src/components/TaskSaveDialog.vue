<template>
    <v-dialog v-model="show" width="auto">
        <v-sheet width="350" class="mx-auto" :color="editTaskMoodColor">
            <v-form @submit.prevent.stop="submitForm" ref="form">

                <v-text-field
                    v-model="editableItem.name"
                    label="Task Name"
                    :rules="rules.name"
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
                    <v-btn color="primary" type="submit">Save</v-btn>
                    <v-btn color="grey" class="ml-8" @click.prevent="close">Cancel</v-btn>
                </div>
            </v-form>
        </v-sheet>
    </v-dialog>
</template>

<script>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

export default {
    name: "TaskSaveDialog",
    components: {
        VueDatePicker
    },
    props: {
        item: {},
    },
    data() {
        return {
            show: false,
            editableItem: {},
            rules:{
                name: [
                    value => {
                        if (value) return true

                        return 'You must enter a task name.'
                    },
                ],
            }
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
    },
    methods:{
        async submitForm(){
            this.$refs.form.resetValidation()
            const validation = await this.$refs.form.validate();
            if(validation.valid){
                this.$emit('submit', this.editableItem)
            }
        },
        open(item){
            this.editableItem = {...item};
            this.show = true;
        },
        close(){
            this.show = false;
        }
    }
}
</script>

<style scoped>

</style>
