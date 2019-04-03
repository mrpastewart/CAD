<template lang="html">
    <div class="mdt-container">
      <div class="mdt-panel">
        <h2 class='text-center'>Sign in</h2>
        <div class="alert alert-danger mt-3 mb-1" role="alert" v-if="error">
            <div class="text-center text-bold">
                <p>{{error}}</p>
            </div>
        </div>
        <form  style='padding:10px;'>
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" placeholder="[123] J.Doe" v-model='name'>
            </div>
            <div class="form-group" >
                <label>Shift</label>
                <select type="text" class="form-control" v-model='shift'>
                    <option value="" selected disabled>Select a shift</option>
                    <option v-for="shift in shifts" v-bind:value="shift.id">{{shift.name}}</option>
                </select>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="passenger">
                <label class="form-check-label">
                    I am accompanying another officer
                </label>
            </div>
            <div class="bordered-container" v-if="passenger">
                <div class="bordered-container__heading"><h5>Vehicle details</h5></div>
                <div class="bordered-container__content">
                    <div class="form-group">
                        <label>Callsign</label>
                        <select type="text" class="form-control" v-model="passengerCallsign">
                            <option value="" selected disabled>Select a callsign</option>
                            <option>CN20</option>
                        </select>
                        <small class="form-text">The person you are accompanying must have already signed up for you to see the callsign in the list.</small>
                    </div>
                </div>
            </div>
            <div class="bordered-container" v-if="!passenger">
                <div class="bordered-container__heading"><h5>Vehicle details</h5></div>
                <div class="bordered-container__content">
                    <div class="form-group">
                        <label>Callsign</label>
                        <input type="text" class="form-control" placeholder="CN20" v-model='callsign'>
                    </div>
                    <div class="form-group" >
                        <label>Department</label>
                        <select type="text" class="form-control" v-model="division">
                            <option value="" selected disabled>Select a division</option>
                            <option>Frontline Policing</option>
                            <option>Crime Squad</option>
                            <option>Traffic</option>
                            <option>Trojan</option>
                            <option>RMU</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button @click="login" class="btn btn-primary">Sign in</button>
            </div>
        </form>
      </div>
    </div>
</template>

<script>
export default {
    data: function()
    {
        return {
            passenger: false,
            name: "",
            shift: "",
            division: "",
            callsign: "",
            passengerCallsign: "",
            error: false,
            shifts: []
        };
    },
    mounted() {
        axios.get('/api/shifts').then((response) => {
            this.shifts = response.data;
        });
    },
    methods: {
        login(event) {
            event.preventDefault();
            this.error = false;
            axios.post('/auth/login', {
                'passenger': this.passenger,
                'name': this.name,
                'shift': this.shift,
                'division': this.division,
                'callsign': (this.passenger ? this.passengerCallsign : this.callsign),
            }).catch((error) => {
                if (error.response.data.error) {
                    this.error = error.response.data.error;
                }
            }).then((response) => {
                if (response.status === 200) {
                    window.location.href = '/mdt';
                }
            });
        },
    }
}
</script>

<style lang="css" scoped>
</style>
