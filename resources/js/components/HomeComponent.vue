<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><b>Disk Usage Overview</b></div>

                    <div class="card-body">
                        <b-container class="bv-example-row">
                            <b-row>
                                <b-col>Total Size</b-col>
                                <b-col cols="10">{{output.data.size_megabyte }} MB
                                    ({{output.data.size_byte}} byte)</b-col>
                            </b-row>
                            <b-row>
                                <b-col>No of Files</b-col>
                                <b-col cols="10">{{output.data.count }}</b-col>
                            </b-row>
                        </b-container>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><b>Disk Usage Compositions</b></div>

                    <div class="card-body">
                        <table class="table" align="center">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>No of files</th>
                                <th>Size</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="conposition in output.data.conpositions">
                                <td>{{conposition.name}}</td>
                                <td>{{conposition.total}}</td>
                                <td>{{conposition.size_megabyte }} MB
                                    ({{conposition.size_byte}} byte)</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('done');
        },
        data () {
            return {
                loading: false,
                output: {
                    data : {
                        size_byte : 0,
                        size_megabyte : 0.00,
                        count : 0,
                    }
                }
            }
        },
        created: function () {
            this.curlImageInfo()
        },
        methods: {
            curlImageInfo () {
                let loader = this.$loading.show({
                    // Optional parameters
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                    onCancel: this.onCancel,
                });
                let currentObj = this
                this.axios.get('http://localhost:8000/api/images')
                    .then(function (response) {
                        currentObj.output = response.data
                        loader.hide()
                    })
                    .catch(function (error) {
                        currentObj.output = error

                    })
            },
        }
    }
</script>
