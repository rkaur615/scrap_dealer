<template>

    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
        <h3 class="mb-5" style="font-size: 30px;font-weight: bold;color: #000;">Account Setting</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                      <label>Name</label>
                      <input type="text" id="name" class="form-control" :value="user?.name">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                      <label>Email</label>
                      <input disabled type="text" id="email" class="form-control" :value="user?.email">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                      <label>Phone number</label>
                      <input type="text" class="form-control" id="phone_number" :value="user?.phone_number">
                </div>
            </div>


        </div>
        <div>
            <button class="btn btn-primary" @click="updateDetails()">Update</button>

        </div>
    </div>

</template>
<script setup>
import {onMounted, ref} from 'vue';
import axios from '../axioslib';
import { notify } from "@kyvg/vue3-notification";

let old_password = ref("");
let new_password = ref("");
let re_password = ref("");
let title = ref("Hi");
let user = ref({});

onMounted(async ()=>{
    let {data} = await axios.get('user/details');
    user.value = data.data;
    console.log("Here is detail",user);
});


let checkForm = () => {
console.log("Update clicked", old_password.value, new_password.value, re_password, new_password.value != re_password.value)
if(new_password.value != re_password.value){

    document.querySelector("#rePass").setCustomValidity('Password Must be Matching.');
}
else{
    document.querySelector("#rePass").setCustomValidity('');
}
}
let updateDetails = async () =>{
    console.log('updateDetails-->','data');
    let name = document.querySelector("#name");
    let phone_number = document.querySelector("#phone_number");
    try{
        let resp = await axios.post('user/update/personal',{name:name.value,phone:phone_number.value})
        console.log("here is resp", resp);
        // this.$vToastify.success();
        let type=resp.status==200?"success":"error";
        notify({
            title: resp.data.msg,
            type
            // text: "Password Updated Successfully",
        });
    }
    catch(err){
        console.log(err)
        console.log(err.response.data.msg)
        notify({
            title: err.response.data.msg,
            type: "error"
            // text: "Password Updated Successfully",
        });
    }

};



</script>
