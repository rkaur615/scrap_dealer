<template>
<form method="post" @submit.prevent="saveProfile" v-if="categories_opts.length">
<div class="signUpmainForm"><br><br>
    <div class="headerForm" v-if="toBeShown">
        <h1 style="font-weight: bold;font-size: 40px;font-family: inherit;font-style: normal;color: #000;">Welcome to <img :src="baseURL+'/assets/images/logo.png'"></h1>
        <p>Enter your Business details below </p>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="formGroup">
                <label for="business_name" class="form-label">Business Name / {{role=='SUPPLIER'?'Supplier':'Retailer'}} Name</label>
                <input required type="text" class="form-control" v-model="name" id="business_name" >
            </div>
        </div>
        <div class="col-md-6">
            <div class="formGroup">
                <label for="services" class="form-label" v-if="role=='SUPPLIER'">Categories</label>
                <label for="services" class="form-label" v-else>Services</label>
                <Multiselect
                    v-model="cats"
                    mode="tags"
                    :required="true"
                    :close-on-select="true"
                    :groups="true"
                    :options="categories_opts"
                    />
                <!-- <select required name="services" v-model="cats" multiple id="services" class="form-control">
                    <template v-for="cat in categories" :key="cat.id">
                        <option v-if="!cat.parent_id" disabled :value="cat.id" >{{cat.title}}</option>
                        <template v-else>
                            <option  :value="cat.id" >{{cat.title}}</option>
                        </template>

                    </template>

                </select> -->
            </div>
        </div>
        <div class="w-100 d-none d-md-block"></div>
        <div class="col-md-12">
            <div class="formGroup">

                <!-- <l-map style="height:50vh" ref="map" :center="[41.89026, 12.49238]">
                    <l-tile-layer
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    layer-type="base"
                    name="OpenStreetMap"
                  ></l-tile-layer>
                  </l-map> -->
                <label for="address" class="form-label">Address 1</label>
                <input type="text" name="address" v-model="address" class="form-control" id="address" placeholder="" />
            </div>
        </div>
        <div class="col-md-12">
            <div class="formGroup">

                <!-- <l-map style="height:50vh" ref="map" :center="[41.89026, 12.49238]">
                    <l-tile-layer
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    layer-type="base"
                    name="OpenStreetMap"
                  ></l-tile-layer>
                  </l-map> -->
                <label for="address" class="form-label">Address 2</label>
                <input ref="autocomplete"
                placeholder="Enter your address"
                type="text"
                class="form-control" id="address"
                name="address2" v-model="address2"/>
        <br/>
        <small>For Distance Measurement</small>
                <!-- <input type="text" name="address" v-model="address" class="form-control" id="address" placeholder=""> -->
            </div>
        </div>
        <div class="col-md-6" v-if="role=='SUPPLIER'">
            <div class="formGroup">
                <label for="address" class="form-label">Delivery Radius</label>
                <input type="text" name="radius" v-model="radius" class="form-control" id="address" placeholder="Delivery Radius">
            </div>
        </div>
        <!-- <div class="col-md-12">
            <div class="formGroup">
                <label for="address1" class="form-label">Address 1</label>
                <input type="text" name="address1" v-model="address2" class="form-control" id="address1" placeholder="">
            </div>
        </div> -->
         <div class="col-md-6">
            <div class="formGroup" v-if="countries.length">

                <label for="country" class="form-label">Country</label>
                <!-- <input type="text" class="form-control" @change="updateFilter($event.target)"/>
                <ul v-if="shouldShow">
                    <li v-for="country in countries" :key="country.id">{{country.name}}</li>
                </ul> -->
                <v-select v-model="selectedCountry"  @option:selected="countrySelected" :options="countries"></v-select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="formGroup">
                <label for="state" class="form-label">State</label>
                <v-select v-model="selectedState" @option:selected="stateSelected" :options="states"></v-select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="formGroup">
                <label for="city" class="form-label">City</label>
                <v-select v-model="selectedCity" @option:selected="citySelected" :options="cities"></v-select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="formGroup">
                <label for="zip" class="form-label">Zip</label>
                <input type="text" name="zip" class="form-control" id="zip" v-model="zip" placeholder="">
            </div>
        </div>

        <div v-if="role=='SUPPLIER'">
            Area of Operation
        </div>

        <div class="col-md-12">
            <div class="formGroup">
                <label for="businessHours" class="form-label">Business Hours</label>

                <div class="card border-0">

                    timeSlot--{{timeSlot}}
                        <div class="row" v-for="timeSlot in timeSlots" :key="timeSlot.id">

                            <div class="col-sm-2"> <label class=" mt-4">Open Hours</label> </div>
                            <div class="col-sm-10 list">
                                <div class="mb-2 row justify-content-between px-3 timingSetArea">
                                    <select @change="updateDay($event.target, timeSlot)" class="mb-2 mob" style="width: 130px;height: 25px;margin-top: 20px;">
                                        <template v-for="day in days" :key="day">
                                            <option :selected="timeSlot.data.day==day" :value="day" >{{day}}</option>
                                        </template>


                                    </select>
                                    <div class="mob" style="width: 160px;">
                                        <label class=" mr-1">From</label>
                                        <input class="ml-1" required @change="updateTime($event.target, timeSlot, 'from')" type="time" name="from" :value="timeSlot.data.from" style="width: 100%;">
                                    </div>
                                    <div class="mob mb-2" style="width: 160px;">
                                        <label class=" mr-4">To</label>
                                        <input required @change="updateTime($event.target, timeSlot, 'to')" class="ml-1" type="time" name="to" :value="timeSlot.data.to"  style="width: 100%;">
                                    </div>
                                    <div @click="removeSlot(timeSlot)" class="mt-1 cancel text-danger" style="margin-top: 20px !important;color: red !important;width: 40px;">
                                        <span class="btn btn-danger btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <a class="btn btn-primary" @click="addNewTimeSlot()" style="padding: 3px 10px;"><i class="fa fa-plus-circle"></i>&nbsp;Add</a>
                            </div>
                        </div>

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="formGroup">
                <label for="aboutUs" class="form-label">About Us</label>
                <textarea class="form-control" v-model="aboutme" rows="3" style="border: 1px solid #ccc;">{{profile.about_us}}</textarea>
            </div>
        </div>

        <div class="col-md-12" v-if="hasImage()">
            <div class="formGroup">
                <label for="aboutUs" class="form-label">Files</label>
                <div class="w-100 d-none d-md-block"></div>
                <div class="imagesAttached">
                    <div class="imagesAttachedBox">
                        <img :src="baseURL+'/storage/'+files[0]['filepath']" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="formGroup">
                <label for="aboutUs" class="form-label">Upload Image</label>
                <div class="w-100 d-none d-md-block"></div>
                <input type="file" @change="upload" accept="image/*"  id="uploads" multiple="multiple" class="btn btn-success" >
            </div>
        </div>

    </div>
</div>
<div class="mb-3 mt-5 text-center">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
</form>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<script>
import axios, {baseURL} from '../axioslib';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

import 'devextreme/dist/css/dx.light.css';

import Multiselect from '@vueform/multiselect'

const app = {
    components: {
        'v-select': vSelect,
        Multiselect,

    },
    props:[
        "userid",
        'role',
        'toBeShown'
    ],
    computed:{

        tformat(id){
            return {
                id,
                data:{}
            }
        }
    },

    data(){
        return {
            baseURL,
            headerToBeShown:true,
            name:"",
            service:"",
            address:"",
            address2:"",
            aboutme:"",
            categories_opts:[],
            radius:0,
            zip:"",
            files:[],
            selectedCountry:{},
            selectedState:{},
            selectedCity:{},
            cats:[],
            countries:[],
            states:[],
            cities:[],
            categories:[],
            profile:{},
            country_id:0,
            state_id:0,
            selectedCats:[],
            city_id:0,
            lat:0,
            lng:0,
            shouldShow:false,
            timeSlots:[
                {
                    id:0,
                    data:{
                        day:"Sunday",
                        from:"",
                        to:""
                    }
                }
            ],
            days: [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday"
            ],
            geojson: {
                type: "FeatureCollection",
                features: [
                // ...
                ],
            },
            geojsonOptions: {
                // Options that don't rely on Leaflet methods.
            },
        }
    },

    async mounted(){

        //.map(item=> {return {"code":item.id, "label":item.name}})
        //return address
        this.countriesRes = await axios.get(`search/countries/all`);
        this.countries = this.countriesRes.data.data.map(item=> {return {"code":item.id, "label":item.name}})

        let cType = this.role=="SUPPLIER"?1:2
        this.categoriesRes = await axios.get(`search/categories?type=${cType}`);
        this.categories = this.categoriesRes.data.data
        let parent_cats = this.categoriesRes.data.data.filter(item=>!item.parent_id);
        parent_cats.forEach((opt)=>{
            this.categories_opts.push({'label':opt.title, options:this.categoriesRes.data.data.filter(item=>item.parent_id==opt.id).map(item=>{ return {"value":item.id,"label":item.title}; })});
        });
        let profileRes = await axios.get(`user/profile`);
        let profile = profileRes.data.profile
        if(profile && profile.hasOwnProperty('country_id')){
            this.selectedCountry = this.countries.filter(st=>st.code==profile.country_id)[0];
        }

        var autocomplete = new google.maps.places.Autocomplete(this.$refs.autocomplete, {
        types: ["establishment"]});

      autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        this.address2 = place.formatted_address
        console.log(place.geometry.location.lat(), place.geometry.location.lng());
        this.lat = place.geometry.location.lat();
        this.lng = place.geometry.location.lng();
        console.log(place);
       });


        console.log("am profile data", profile);
        if(profileRes.data.hasOwnProperty('profile') && profile && profile.name!=''){
            this.cats = profileRes.data.cats.map(item=>item.category_id)
            console.log("selectedCats", this.selectedCats)
            this.profile = profileRes.data.profile;
            this.files = profileRes.data.files;
            this.countrySelected({code:profile.country_id});
            // this.selectedState({code:profile.state_id})
            this.stateSelected({code:profile.state_id});
            console.log('all states', this.states)
            this.name = profile.name;
            this.aboutme = profile.about_us;
            this.address = profile.address;
            this.radius = profile.radius;
            this.address2 = profile.address2;
            this.zip = profile.pin;
            this.timeSlots = profile.time_slots;
            console.log("here is profile", profile, this.timeSlots);
        }





    },
    methods: {
        hasImage(){
            return this.files.length;
        },
        async upload(){
            console.log('uploads Called');
            var formData = new FormData();
            formData.append("filetype", 1);

            var files = document.querySelector('#uploads');
            console.log('files',files.files);
            let temp1 = files.files;
            Object.keys(temp1).forEach(item=>{ formData.append("files[]", temp1[item]); })
            // .forEach(elem=>{
            //     formData.append("files", elem);
            // });

            axios.post('user/uploadFiles', formData, {
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })
        },
        updateFilter(target){
            let q = target.value
            if(q.length){
                this.shouldShow = true;
                this.countries = this.countries.filter(item => item.name.includes(q))
            }
            else{
                this.shouldShow = false;
            }
        },
        async countrySelected(selectedOption){
            console.log("Country Selected",selectedOption);
            this.country_id = selectedOption.code;
            this.statesRes = await axios.get(`search/states?country_id=${selectedOption.code}`);
            this.states = this.statesRes.data.data.map(item=> {return {"code":item.id, "label":item.name}})
            console.log("selected states", this.states.filter(st=>st.code==this.profile.state_id))
            this.selectedState = this.states.filter(st=>st.code==this.profile.state_id)[0];
        },
        async stateSelected(selectedOption){
            console.log("State Selected",selectedOption);
            this.state_id = selectedOption.code;
            this.citiesRes = await axios.get(`search/cities?country_id=${this.country_id}&state_id=${selectedOption.code}`);
            this.cities = this.citiesRes.data.data.map(item=> {return {"code":item.id, "label":item.name}})
            this.selectedCity = this.cities.filter(st=>st.code==this.profile.city_id)?this.cities.filter(st=>st.code==this.profile.city_id)[0]:{};
            this.city_id = this.profile.city_id;
            console.log("selected city", this.cities.filter(st=>st.code==this.profile.city_id))
        },
        // stateSelected(selectedOption){
        //     console.log("State Selected",selectedOption);
        //     this.state_id = selectedOption.code;
        // },
        citySelected(selectedOption){
            console.log("City Selected",selectedOption);
            this.city_id = selectedOption.code;
        },
        updateTimeSlots(){
            //axios.post('/user/save/timeslots',{timeSlots:this.timeSlots,userid:this.userid})

        },
        updateTime(target, data, type){
            data['data'][type] = target.value;
            console.log("am val")
            console.log(this.timeSlots);
            this.updateTimeSlots();

        },
        addNewTimeSlot(){
            console.log(this.timeSlots)
            this.timeSlots.push(
                {
                    id:this.timeSlots.length,
                    data:{
                        day:"",
                        from:"",
                        to:""
                    }
                }
            )
        },
        updateDay(target, data){

            console.log(target.value, data);
            data.data.day = target.value
            console.log(this.timeSlots);
            this.updateTimeSlots();
        },
        removeSlot(slot){
            if(this.timeSlots.length>1){
                this.timeSlots = this.timeSlots.filter((item, index)=>index !== slot.id).map((itm, i) => {itm.id = i; return itm;} )
            }

            console.log('slot==->',slot);
        },
        async fetchCountries(search, loading){
            console.log("I am search", search, loading);
            this.countriesRes = await axios.get(`search/countries?q=${search}`);
            this.countries = this.countriesRes.data.data
            //return address
            console.log("here is address", this.countries);
        },

        async saveProfile(){
            console.log("profile saving");
            let dataToBeSaved = {
                name: this.name,
                service:this.service,
                address:this.address,
                radius:this.radius,
                address2:this.address2,
                aboutme:this.aboutme,
                cats:this.cats,
                lat:this.lat,
                lng:this.lng,
                country_id:this.country_id,
                state_id:this.state_id,
                city_id:this.city_id,
                timeSlots:this.timeSlots,
                zip:this.zip,
                userid:this.userid
            }

            let response = await axios.post('user/save/timeslots',dataToBeSaved)
            console.log('response',response.data)
            location.href = response.data.to;
        }
    }
}
export default app;
</script>
