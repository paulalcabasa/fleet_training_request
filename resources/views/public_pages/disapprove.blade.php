<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="Prince Ivan Kent Tiburcio">
        <link rel="shortcut icon" type="image/x-icon" href="{{{ url('public/favicon.ico') }}}">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ url('public/js/client.js') }}"></script>

        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />

        <!-- Styles -->
        <link rel="stylesheet" href="{{ url('public/libraries/css/vuetify.min.css') }}">
        <link rel="stylesheet" href="{{ url('public/libraries/adminlte/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ url('public/libraries/adminlte/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ url('public/css/styles.css') }}">
    </head>
    <body>
        <v-app id="app" v-cloak style="background-color: #222D32;">
            <v-toolbar 
            class="header"
            app fixed clipped-left style="background-color: #424242; z-index: 10;">
                <v-toolbar-title style="padding-top: 8px;">
                    <img src="{{ url('public/images/isuzu-logo-compressor.png') }}" alt="image not found" style="height: 35px;">
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn flat color="white">Fleet Training Request System</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            
            <v-content dark>
                <v-container>
                    <template>
                        <div>

                            <v-alert
                            :value="true"
                            type="error"
                            v-if="status == 'denied'"
                            >
                            The request has already been disapproved.
                            </v-alert>

                          <v-alert
                            :value="true"
                            type="error"
                            v-if="message != ''"
                            >
                            @{{ message }}
                            </v-alert>
                            <v-form v-model="valid" v-if="message == '' && status == 'pending'">
                                <v-container fluid>
                                    <v-card  class="mx-auto"
                                        max-width="400"
                                        outlined>
                                        <v-card-title>Are you sure to disapprove the training request?</v-card-title>
                                        <v-layout justify-center row wrap>
                                            <v-flex xs10 sm10>
                                                <v-textarea   
                                                    v-model="reason"
                                                    :rules="reasonRule"
                                                    :auto-grow="true"
                                                    placeholder="Please enter your reason"
                                                    rows="5"
                                                ></v-textarea>  
                                            </v-flex>
                                        </v-layout>
                                        <v-card-actions>
                                            <v-btn
                                                :disabled="!valid"
                                                color="success"
                                                class="mr-4"
                                                @click="disapprove"
                                            >
                                            Confirm
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-container>
                            </v-form>
                        </div>
                    </template>
                </v-container>
            </v-content>

            <v-footer class="footer" app fixed>
                <span>&copy; 2017</span>
            </v-footer>
        </v-app>

        <script src="{{ url('public/libraries/js/vuetify.min.js') }}"></script>
        <script>
            var app = new Vue({
                el: '#app',
                data() {
                    return {
                        reason : '',
                        valid: false,
                          reasonRule: [
                            v => !!v || 'Reason is required',
                            v => v.length > 10 || 'Reason must be more than 10 characters',
                        ],
                        approval_status_id : {!! json_encode($data['approval_status_id']) !!},
                        base_url : {!! json_encode($data['base_url']) !!},
                        message : '',
                        status : {!! json_encode($data['status']) !!}
                    }
                },
                created() {},
                methods: {
                    disapprove(){
                        var self = this;
                        axios.post(self.base_url + '/superior/disapprove_request',{
                            approval_status_id : self.approval_status_id,
                            reason             : self.reason
                        }).then( (response) => {
                            self.message = response.data.message;
                        });
                    }
                }
            })
        </script>
    </body>
</html>
