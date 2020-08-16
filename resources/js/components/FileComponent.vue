<template>
    <div class="container">
        <div class="large-12 medium-12 small-12 filezone">
            <input type="file" id="files" ref="files" multiple v-on:change="handleFiles()"/>
            <div align="center" class="blog">
                <i class="fa fa-5x fa-cloud-upload"></i><br>
                <span>Drop your files here or Click to choose files</span>
            </div>
        </div>
        <br>
        <b-container class="bv-example-row">
            <b-row>
                <b-col cols="4" v-for="(file,key) in files" v-bind:key="file.id">
                    <img v-img class="preview" :src="file.image_url"/>
                    <br>
                    <div class="action-container">
                        <b-button v-on:click="removeFile(key,file.id)" variant="danger">
                            <i class="fa fa-trash-o"></i>
                        </b-button>
                    </div>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                files: [],
            }
        },
        methods: {
            showModal() {
                let element = this.$refs.modal.$el
                $(element).modal('show')
            },
            handleFiles() {
                let loader = this.$loading.show({
                    // Optional parameters
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                    onCancel: this.onCancel,
                });
                let uploadedFiles = this.$refs.files.files;
                for (var i = 0; i < uploadedFiles.length; i++) {
                    let formData = new FormData();
                    formData.append('file', uploadedFiles[i]);
                    axios.post('http://localhost:8000/api/images/upload',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            },
                        }
                    ).then(data => {
                        this.files.push(data.data.data);
                        loader.hide()
                    }).catch(error => {
                        console.log(error);

                    });
                }
                this.getImagePreviews();
            },
            getImagePreviews() {
                for (let i = 0; i < this.files.length; i++) {
                    if (/\.(jpe?g|png)$/i.test(this.files[i].name)) {
                        let reader = new FileReader();
                        reader.addEventListener("load", function () {
                            this.$refs['preview' + parseInt(i)][0].src = reader.result;
                        }.bind(this), false);
                        reader.readAsDataURL(this.files[i]);
                    } else {
                        this.$nextTick(function () {
                            this.$refs['preview' + parseInt(i)][0].src = '/images/danger.jpg';
                        });
                    }
                }
            },
            removeFile(key,id) {
                let loader = this.$loading.show({
                    // Optional parameters
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                    onCancel: this.onCancel,
                });
                axios.delete('http://localhost:8000/api/images/remove/' + id
                ).then(function (data) {
                    this.files.splice(key, 1);
                    this.getImagePreviews();
                    loader.hide()
                }.bind(this)).catch(function (data) {
                    console.log('error');
                });
            }
        }
    }
</script>

<style scoped>
    .blog {
        margin-top: 5%;
    }

    input[type="file"] {
        opacity: 0;
        width: 100%;
        height: 200px;
        position: absolute;
        cursor: pointer;
    }

    .filezone {
        outline: 2px dashed grey;
        outline-offset: -10px;
        background: #ccc;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px;
        position: relative;
        cursor: pointer;
    }

    .filezone:hover {
        background: #c0c0c0;
    }

    .filezone p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 50px 50px 50px;
    }

    div.file-listing img {
        max-width: 90%;
    }

    div.file-listing img {
        height: 100px;
    }

    div.success-container {
        text-align: center;
        color: green;
    }

    div.remove-container a {
        color: red;
        cursor: pointer;
    }

    .preview {
        width: 100%;
    }

    div.action-container {
        margin-top: 20px;
        text-align: center;
    }
</style>