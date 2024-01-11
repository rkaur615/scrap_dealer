<template>

        <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
            <form method="post" @submit.prevent="changePassword">
            <h3 class="mb-5" style="font-size: 30px;font-weight: bold;color: #000;">Password Settings</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Old password</label>
                        <input type="password" required v-model="old_password" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>New password</label>
                        <input type="password" required v-model="new_password" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Confirm new password</label>
                        <input type="password" id="rePass" required @change="checkForm" v-model="re_password" class="form-control">
                    </div>
                </div>
            </div>
            <div>
                <button class="btn btn-primary" type="submit">Update</button>

            </div>
        </form>
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

let checkForm = () => {
    console.log("Update clicked", old_password.value, new_password.value, re_password, new_password.value != re_password.value)
    if(new_password.value != re_password.value){

        document.querySelector("#rePass").setCustomValidity('Password Must be Matching.');
    }
    else{
        document.querySelector("#rePass").setCustomValidity('');
    }
}
let changePassword = async () =>{
    console.log("Update clicked", old_password.value, new_password.value, re_password, new_password.value != re_password.value)
    if(new_password.value != re_password.value){

        document.querySelector("#rePass").setCustomValidity('Password Must be Matching.');
    }
    else{
        document.querySelector("#rePass").setCustomValidity('');
        try{
            let resp = await axios.post('user/update/password',{old_password:old_password.value,new_password:new_password.value,re_password:re_password.value})
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
    }

};



</script>
