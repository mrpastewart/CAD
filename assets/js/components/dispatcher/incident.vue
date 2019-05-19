<template lang="html">
    <div class="col-lg-6">
        <div class="dispatcher-panel__container ">
            <div class="dispatcher-panel__title dispatcher-panel__title--selected">
                Details
            </div>
            <div class="dispatcher-panel__block dispatcher-panel__block--secondary">
                <div class="dispatcher-panel__body">
                    <div class='width:100%;'>
                        <div class="btn btn-small btn-primary btn-sm"  @click="close">
                            <i class="fas fa-angle-double-left"></i>
                            Back
                        </div>
                        <div class="ml-auto btn btn-small btn-success btn-sm"  @click="reopen" v-if="!editing && (incident.status == 4 || incident.status == 5)">
                            <i class="fas fa-edit"></i>
                            Reopen
                        </div>
                        <div class="ml-auto btn btn-small btn-primary btn-sm"  @click="edit" v-if="!editing && (incident.status != 4 && incident.status != 5)">
                            <i class="fas fa-edit"></i>
                            Edit
                        </div>
                        <div class="ml-auto btn btn-small btn-danger btn-sm"  @click="deleteIncident" v-if="editing">
                            <i class="fas fa-trash"></i>
                            Delete
                        </div>
                        <div class="ml-auto btn btn-small btn-success btn-sm"  @click="save" v-if="editing">
                            <i class="fas fa-save"></i>
                            Save
                        </div>
                    </div>
                    <div v-if="!editing">
                        <h4>{{incident.title}}&nbsp;<span class="badge" v-bind:class='{ "badge-danger": (indiaGrade), "badge-success": (!indiaGrade) }'>{{incident.grading}} Grade</span></h4>
                        <p>ID: {{incident.id}}</p>
                        <p>Interop channel: {{incident.interop}}</p>
                        <p>Location:{{incident.location1}}&nbsp;,{{incident.location2}}</p>
                        <p>Type:{{incident.type}}</p>
                        <br />
                        <p class='pre-wrap'>{{incident.details}}</p>
                    </div>
                    <div v-if="editing">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" v-model="editFields.title" >
                        </div>
                        <div class="form-group">
                            <label>Interop</label>
                            <input type="number" class="form-control" v-model="editFields.interop" >
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" class="form-control" v-model="editFields.type" >
                        </div>
                        <div class="form-group">
                            <label>Location 1</label>
                            <input type="text" class="form-control" v-model="editFields.location1" >
                        </div>
                        <div class="form-group">
                            <label>Location 2</label>
                            <input type="text" class="form-control" v-model="editFields.location2" >
                        </div>
                        <div class="form-group">
                            <label>Grading</label>
                            <select type="text" class="form-control" v-model="editFields.grading" >
                                <option value="I">India</option>
                                <option value="S">Siera</option>
                                <option value="D">Delayed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Details</label>
                            <textarea class="form-control" v-model="editFields.details" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dispatcher-panel__container ">
            <div class="dispatcher-panel__title dispatcher-panel__title--selected">
                Comments
            </div>
            <div class="dispatcher-panel__block dispatcher-panel__block--secondary">
                <div class="dispatcher-panel__body">
                    <div class="">
                        <div class='chat-message__container' v-for='log in logs'>
                            <div class="chat-message-author" v-if='log.type == 1'>
                                <span class="badge badge-pill badge-primary">System</span>
                            </div>
                            <div class="chat-message-author" v-if='log.type == 2'>
                                <span class="badge badge-pill badge-primary">{{log.type}}</span>
                            </div>
                            <div class="chat-message__message pre-wrap">
                                {{log.details}}
                            </div>
                            <div class="chat-message__time">
                                {{log.created_at}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['incident', 'units'],
    data: function () {
        let logs = [];

        if (this.incident.logs) {
            logs = this.incident.logs.reverse();
        }
        return {
            editFields: this.incident,
            indiaGrade: (this.incident.grading == 'I'),
            editing: false,
            logs: logs
        };
    },
    watch: {
        incident: function(newVal, oldVal) {
            this.logs = newVal.logs.reverse();
        }
    },
    methods: {
        close() {
            this.$store.dispatch('clearIncident');
        },
        edit() {
            this.editing = true;
            this.editFields = this.incident;
        },
        save() {
            this.editing = false;
            this.indiaGrade = (this.editFields.grading == 'I');
            let self = this;
            axios.patch('/api/incidents/'+this.incident.id,{
                "title": this.editFields.title,
                "interop": this.editFields.interop,
                "type": this.editFields.type,
                "grading": this.editFields.grading,
                "details": this.editFields.details,
                "location1": this.editFields.location1,
                "location2": this.editFields.location2
            }).then(function() {
                self.$store.dispatch('updateDispatcher');
            });
        },
        reopen() {
            this.editing = false;
            let self = this;
            axios.post('/api/incidents/'+this.incident.id+'/reopen').then(function() {
                self.$store.dispatch('updateDispatcher');
            });
        },
        deleteIncident() {
            axios.delete('/api/incidents/'+this.incident.id);
            // this.close();
        }
    }
}
</script>

<style lang="css" scoped>
</style>
