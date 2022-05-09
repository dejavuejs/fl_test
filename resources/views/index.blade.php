@extends('layouts.master')

@section('content')
    <city></city>
@endsection

@push('scripts')
    <script type="text/x-template" id="city-template">
        <div class="container">
            <!-- Add new city -->
            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                <label for="name" class="required">{{ __('Enter city name') }}</label>

                <input type="text" v-validate="'required'" v-model="name" class="control" name="name" data-vv-as="&quot;{{ __('City name') }}&quot;"/>

                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
            </div>

            <div class="control-group">
                <input type="submit" value="Add City" @click="addCity">
            </div>

            <div class="row" style="margin-top: 15px">
                <h1>Cities list</h1>

                <!-- List all cities and their forecast -->
                <div class="row" v-for="(city, index) in cities">

                    <span class="city-name">
                        @{{ city.name }}
                    </span>

                    <span class="city-forecast" v-if="city.forecast && city.forecast.length > 0">
                        <div v-for="(forecast, dt) in JSON.parse(city.forecast).list">
                            <span>Date: @{{ forecast.dt_txt }}</span> <br/>
                            <span>Temperature: @{{ forecast.main.temp }}</span>
                        </div>
                    </span>

                    <button class="city-forecast" v-if="city.forecast == null" @click="getForecast(city.id, city.name)">
                        Get forecast
                    </button>
                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('city', {
            template: '#city-template',

            inject: ['$validator'],

            data: function() {
                return {
                    name: null,
                    cities: []
                }
            },

            created: function() {
            },

            mounted: function() {
                this.getCities();
            },

            methods: {
                getForecast: async function(id, name) {
                    let __self = this;

                    await axios.post(
                        '{{ route('city.forecast') }}',
                        {
                            id: id,
                            name: name
                        }
                    ).then(function (response) {
                        __self.cities = response.data.data;

                        __self.$root.enableReload();
                    }).catch(function (error) {
                        alert(error.response.data.message);

                        __self.$root.enableReload();
                    });
                },

                getCities: async function() {
                    let __self = this;

                    await axios.get(
                        '{{ route('city.all') }}'
                    ).then(function (response) {
                        __self.cities = response.data.data;

                        // window.flashMessages = [{'type': 'alert-success', 'message': response.data.message }];

                        // __self.$root.addFlashMessages();
                    }).catch(function (error) {
                        alert(error.response.data.message);

                        // window.flashMessages = [{'type': 'alert-error', 'message': error.response.data.message }];

                        // __self.$root.addFlashMessages();
                    });
                },

                addCity: async function() {
                    let __self = this;

                    await axios.post(
                        '{{ route('city.store') }}',
                        {
                            name: this.name
                        }
                    ).then(function (response) {
                        __self.getCities();

                        alert(response.data.message);

                        __self.$root.enableReload();
                    }).catch(function (error) {
                        alert(error.response.data.message);

                        __self.$root.enableReload();
                    });
                }
            }
        });
    </script>

    <style>
    </style>
@endpush
