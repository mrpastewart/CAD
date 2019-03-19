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
                        <div class="ml-auto btn btn-small btn-primary btn-sm"  @click="edit" v-if="!editing">
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
                        <p>Type:{{incident.type}}</p>
                        <br />
                        <p style="white-space: pre-wrap;">{{incident.details}}</p>
                    </div>
                    <div v-if="editing">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" v-model="incident.title" >
                        </div>
                        <div class="form-group">
                            <label>Interop</label>
                            <input type="number" class="form-control" v-model="incident.interop" >
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" class="form-control" v-model="incident.type" >
                        </div>
                        <div class="form-group">
                            <label>Grading</label>
                            <select type="text" class="form-control" v-model="incident.grading" >
                                <option value="I">India</option>
                                <option value="S">Siera</option>
                                <option value="D">Delayed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Details</label>
                            <textarea class="form-control" v-model="incident.details" rows="3"></textarea>
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
                        <div class='chat-message__container'>
                            <div class="chat-message-author">
                                <span class="badge badge-pill badge-primary">CN20</span>
                                [123] J.Doe
                            </div>
                            <div class="chat-message__message">
                                1x injured, requesting LAS.
                            </div>
                            <div class="chat-message__time">
                                09/03/2019 13:00
                            </div>
                        </div>
                        <div class='chat-message__container'>
                            <div class="chat-message-author">
                                <span class="badge badge-pill badge-primary">CN20</span>
                            </div>
                            <div class="chat-message__message">
                                State 6: on scene
                            </div>
                            <div class="chat-message__time">
                                09/03/2019 13:00
                            </div>
                        </div>
                        <div class='chat-message__container'>
                            <div class="chat-message-author">
                                <span class="badge badge-pill badge-primary">System</span>
                            </div>
                            <div class="chat-message__message">
                                RMU124, RMU125 &amp; CN20 have been assigned.
                            </div>
                            <div class="chat-message__time">
                                09/03/2019 12:30
                            </div>
                        </div>
                        <div class='chat-message__container'>
                            <div class="chat-message-author">
                                <span class="badge badge-pill badge-primary">System</span>
                            </div>
                            <div class="chat-message__message">
                                CAD created
                            </div>
                            <div class="chat-message__time">
                                09/03/2019 12:30
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
    props: ['inputIncident', 'bus'],
    data: function () {
        return {
            'incident': this.inputIncident,
            'indiaGrade': (this.inputIncident.grading == 'I'),
            editing: false
        };
    },
    methods: {
        close() {
            this.bus.$emit('closeIncident');
        },
        edit() {
            this.editing = true;
        },
        save() {
            this.editing = false;
            this.indiaGrade = (this.inputIncident.grading == 'I');
            axios.patch('/api/incidents/'+this.incident.id,{
                "title": this.incident.title,
                "interop": this.incident.interop,
                "type": this.incident.type,
                "grading": this.incident.grading,
                "details": this.incident.details
            });
        },
        deleteIncident() {
            axios.delete('/api/incidents/'+this.incident.id);
            this.close();
        }
    }
}
</script>

<style lang="css" scoped>
</style>
