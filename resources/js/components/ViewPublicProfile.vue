<template>
    <div class="container" v-if="udata?.pic">
        <div class="headingSection">
            <h2>{{udata.name}}'s Profile</h2>
        </div>

        <div class="containerArea userProfileViewMain" >
            <div class="row">
                <div class="col-4 col-md-3 order-2 order-md-1" >
                    <img :src="baseURL+(udata?.pic)" alt="Image" class="shadow profileImg" style="max-width: 180px;">
                </div>
                <div class="col-8 col-md-6 order-3 order-md-2">
                    <div class="userInfo">
                        <p><strong>Name:&nbsp;</strong>{{udata.name}}</p>
                        <p><strong>Email:&nbsp;</strong>{{udata.email}}</p>
                        <p><strong>Contact:&nbsp;</strong>{{udata.phone_number}}</p>

                        <p v-if="udata.user_role.user_type_id==1"><strong>Category:&nbsp;</strong>
                            <template  v-for="cat in udata.categories" :key="cat.id">
                                <span :class="{'badge':true, [getColor()]:true}">{{cat.category.title}}</span>&nbsp;
                            </template>
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-3 order-1 order-md-3">
                    <div class="ratingReview">
                        <div class="usertypeInfo" style="">{{udata.user_role.user_type_id==1?'Supplier':'Retailer'}}</div>
                        <div class="reviews" v-if="average">

                                <i class="fa fa-star" v-for="i in Math.ceil(average)" :key="'r_'+i"></i>


                                <i class="fa fa-star-o" v-for="j in Math.floor(5-average)" :key="'j_'+j"></i>


                        </div>

                        <!-- <a href="#">See Review</a> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="businessDetails">
            <h4>Business Details</h4>
            <div class="businessInfoMain">
                <p><strong>Business Name:&nbsp;&nbsp;</strong>{{udata?.addresses.name}}</p>
                <p><strong>Address:&nbsp;&nbsp;</strong>{{udata?.addresses.address}}</p>
                <div class="row workingHours">
                    <div class="col-12 col-md-2 "><strong>Working Days/Hours:</strong></div>
                    <div class="col-12 col-md-8">
                        <table class="">
                            <tbody>
                                <tr :key="slot.id" v-for="slot in udata.addresses.time_slots">
                                    <td width="150">{{slot.data.day}}</td>
                                    <td>{{slot.data.from}} - {{slot.data.to}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="businessDetails" style="overflow: hidden;height: auto;">
            <h4>Catalog</h4>
            <div class="ProductListingMain">
                <template v-if="udata.catalog.length">
                    <div class="ProductBox" v-for="cat in udata.catalog" :key="cat.id">
                        <!-- <div class="productImg">
                            <img src="assets/images/dummyImg.png" width="" class="img-fluid">
                        </div> -->
                        <div class="productInfo">
                            <h3><a href="">{{cat.title.title}}</a></h3>
                            <div class="float-start priceItem">{{cSymbol}} {{cat.price}}</div>
                            <div class="float-end priceWeight">{{cat.quantity}} {{cat.unit}}</div>
                        </div>
                    </div>

                </template>
                <div v-else class="alert alert-danger">
                    No products added in Catalog!
                </div>


            </div>
        </div>

        <div>
            <div class="testimonial-box-container businessDetails" v-if="udata?.feedback" style="justify-content: left;">
                <h4 style="display: block;width: 100%;">Review  & Rating</h4>

                    <div class="testimonial-box" v-for="review in udata.feedback" :key="'rid'+review.id">
                        <div class="box-top">
                            <div class="profile">
                                <div class="profile-img">
                                    <img :src="review.retailer.pic" />
                                </div>
                                <div class="name-user">
                                    <strong>{{review.retailer.name}}</strong>
                                </div>
                            </div>
                            <div class="reviews" v-if="review.rating">
                                <i class="fa fa-star" v-for="i in Math.ceil(review.rating)" :key="'r_'+i"></i>


                                <i class="fa fa-star-o" v-for="j in Math.floor(5-(Math.ceil(review.rating)))" :key="'j_'+j"></i>

                            </div>
                        </div>
                        <div class="client-comment">
                            <p>{{review.feedback}}</p>
                        </div>
                    </div>



            </div>
        </div>
    </div>

</template>

    <script setup>
    import _ from 'lodash'
    import { onMounted, ref,defineProps } from 'vue';
    import axios, { baseURL } from '../axioslib';
    // const ViewPublicProfile = {
        // setup(props){
    const props = defineProps({
        id: Number
    })
    console.log(props)

    let udata = ref({})
    let average = ref(0)
    let cSymbol = ref('$');
    let colors = ref(['bg-success','bg-warning','bg-danger','bg-info', 'bg-primary'])

    function getColor(){
        return _.sample(this.colors);
    };
    onMounted(()=>{
        axios.get(`user/details/${props.id}`).then(data=>{
            udata.value = data.data.data
            average.value = data.data.data.feedback.reduce((total, next) => total + next.rating, 0) / data.data.data.feedback.length;
            console.log("that.product_titles",udata, average);

            cSymbol.value =  data.data.settings.filter(item=>item.title=='currency_symbol')[0]['value'];
        });


    })

    // return{
    //     product_titles,
    // }
        // },

    //}
    // export default ViewPublicProfile;
    </script>
