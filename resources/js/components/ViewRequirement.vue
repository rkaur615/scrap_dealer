<template>
    <section class="wrapperMain">
		<div class="container">
			<div class="headingSection">
				<h2>Invoice</h2>
			</div>
			<div class="containerArea">
				<div class="row">
					<div class="col-xs-12">
						<div class="invoice-title" style="height: auto;overflow: hidden;">
							<h3 class="pull-left" style="font-weight: bold;">{{requirement?.title}}</h3>
                            <div class="pull-right actionBtnView">
							    <!-- <button class="btn btn-success btn-sm" @click="approveQuote()" v-if="showEndActions && canAccept" data-bs-toggle="tooltip" data-bs-placement="top" title="Accept"><i class="fa fa-check" style="font-size: 22px;"></i></button> -->
                                <template v-if="userDetails?.user_role?.user_type_id && userDetails?.user_role?.user_type_id==1">
                                    <button class="btn btn-success btn-sm" @click="saveQuote()" v-if="buttonText != ''">{{buttonText}}</button>&nbsp;
                                </template> 
							  <!-- &nbsp; <button class="btn btn-danger btn-sm" @click="rejectQuote()" v-if="!showEndActions || canReject" data-bs-toggle="tooltip" data-bs-placement="top" title="Reject"><i class="fa fa-remove"  style="font-size: 22px;"></i></button>&nbsp; -->&nbsp;

                                <a v-if="mydetails?.id==requirement?.user_id" :href="'/user/chat/'+requirement?.retailer?.id+'/'+requirement?.id" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o" aria-hidden="true" style="font-size: 22px;"></i></a>

								<a v-else :href="'/user/chat/'+mydetails?.id+'/'+requirement?.id" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o" aria-hidden="true" style="font-size: 22px;"></i></a>

							</div>


						</div>
						<hr>
						<div class="row">
							<div class="col-md-6">
								<address>
								<strong>{{requirement?.retailer?.addresses?.name}}:</strong><br>
                                {{requirement?.retailer?.addresses?.address}}<br>
                                {{requirement?.retailer?.phone_number}}<br>
									<strong>Expected Delivery Date: </strong>{{requirement?.expected_date}}<br>
									<strong>Category:</strong> {{categories}}


								</address>
							</div>
							<div class="col-md-6 text-end">
								<address>

								<strong>{{mydetails?.addresses?.name}}</strong><br>
                                {{mydetails?.addresses?.address}}<br>
                                {{requirement?.retailer?.phone_number}}<br>
									<!-- Apt. 4B<br>
									Springfield, ST 54321 -->
								</address>
							</div>
						</div>


						<div class="table-responsive">
							<table class="table table-condensed">
								<thead>
                                    <tr>
                                        <!-- <th>No.</th> -->
                                        <th>Item</th>
                                        <th>Requirement</th>
                                        <!-- <th>Quoted Quantity</th> -->
                                        <th>Status</th>
                                        <th>Quoted Amount</th>
                                        <template v-if="userDetails?.user_role?.user_type_id && userDetails?.user_role?.user_type_id==2">
                                            <th>Actions</th>
                                        </template>
                                        <!-- <th>Price<br>(Per Item)</th>-->
                                        <!-- <th>Total Price</th> -->
                                    </tr>
								</thead>
								<tbody>
                                    <template v-if="items">

                                        <tr v-for="item in items" :key="item.id">
                                            <!-- <td><strong>{{item.id}}.</strong></td> -->
                                            <td>{{item.title}}</td>
                                            <td>{{item.quantity}}
                                                <!-- :value="requirement?.myquote?.[0]?.quote?.[parseInt(item.id)-1]?.quote_quantity"  -->
                                                &nbsp;{{item.unit}}
                                            </td>
                                            <td>{{statusData(item.status)}}</td>
                                            <!-- <td>

                                                <input type="number" :value="item.quote_quantity" min="0" name="quote_quantity" placeholder="Units" @change="updateItemArr($event, item)" :ref="setItemRef" /></td> -->
                                            <td>

                                                <!-- :value="requirement?.myquote?.[0]?.quote?.[parseInt(item.id)-1]?.quote_amount" -->
                                               <div style="width: 115px;"> <template v-if="item.quote_amount>=0">{{cSymbol}}</template>&nbsp;
                                                <input v-if="item.status == 0 || item.status == -1 || item.status == 2" type="number" :value="item.quote_amount" min="0" placeholder="Amount"  name="quote_amount" @input="updateItemArr($event, item)" :ref="setItemRef" style="width: 85px;"/>
                                                <span v-else>{{item.quote_amount}}</span>
                                                </div>
                                            </td>
                                            <template v-if="userDetails?.user_role?.user_type_id && userDetails?.user_role?.user_type_id==2">
                                                <td>
                                                    <template v-if="item.status ==0 || item.status ==3">
                                                        <a class="btn btn-success btn-sm" @click="acceptQuote(item.sqid)">Approve</a>&nbsp;
                                                        <a class="btn btn-danger btn-sm" @click="rejectQuote(item.sqid)">Reject</a>
                                                    </template>
                                                </td>
                                            </template>
                                            <!-- <td>500</td> -->
                                        </tr>
                                    </template>

									<!-- <tr class="no-line text-end">
										<td colspan="5" class="text-end"><strong>Sub Total:&nbsp;</strong></td>
										<td>500</td>
									</tr>
									<tr>
										<td colspan="5" class="text-end"><strong>GST(10%):&nbsp;</strong></td>
										<td>50</td>
									</tr> -->
									<tr>
										<td colspan="3" class="text-end"><strong>Total Amount:&nbsp;</strong></td>
										<td>{{cSymbol}}{{isNaN(totalPrice)?'0.00':totalPrice}}</td>
                                        <template v-if="userDetails?.user_role?.user_type_id && userDetails?.user_role?.user_type_id==2">
                                            <td>
                                                
                                            </td>
                                        </template>
									</tr>
								</tbody>
							</table>
						</div>
						<br>

						<div class="btnAreaBottom text-center actionBtnView">
							<!-- <button class="btn btn-success btn-sm" @click="approveQuote()" v-if="showEndActions && canAccept" data-bs-toggle="tooltip" data-bs-placement="top" title="Accept"><i class="fa fa-check" style="font-size: 22px;"></i></button> -->
                            <template v-if="userDetails?.user_role?.user_type_id && userDetails?.user_role?.user_type_id==1">
                                <button class="btn btn-success btn-sm" @click="saveQuote()" v-if="buttonText != ''">{{buttonText}}</button>&nbsp;
                            </template>

							<!-- <button class="btn btn-danger btn-sm" @click="rejectQuote()" v-if="!showEndActions || canReject" data-bs-toggle="tooltip" data-bs-placement="top" title="Reject"><i class="fa fa-remove"  style="font-size: 22px;"></i></button>&nbsp; -->
							<!-- <button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o" aria-hidden="true" style="font-size: 22px;"></i></button> -->
                            <!-- <a v-if="mydetails" :href="'/user/chat/'+requirement?.retailer?.id+'/'+requirement?.id" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o" aria-hidden="true" style="font-size: 22px;"></i></a>

								<a v-else href="#" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o" aria-hidden="true" style="font-size: 22px;"></i></a> -->
                                &nbsp;
                                <a v-if="mydetails?.id==requirement?.user_id" :href="'/user/chat/'+requirement?.retailer?.id+'/'+requirement?.id" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o" aria-hidden="true" style="font-size: 22px;"></i></a>

								<a v-else :href="'/user/chat/'+mydetails?.id+'/'+requirement?.id" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"><i class="fa fa-comments-o" aria-hidden="true" style="font-size: 22px;"></i></a>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>
</template>
<script>
import axios from '../axioslib';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import moment from 'moment';

let defItem = {
        id: 1,
        title:"",
        quantity:0,
        // quote_quantity:0,
        quote_amount:0,
        unit:'KG',
        code:0
    };
const AddRequirement = {
    props: ["id","qid"],
    computed:{

        minDate(){
            return moment().format('YYYY-MM-DD')
        },
        total(){
            console.log('total',this.items)

            //return this.items.reduce((p,n)=>{ console.log(p,n, parseFloat (n['quote_amount'])); return p+parseFloat(n['quote_amount']);},0)
        }
    },
    watch:{
        'items': {
            handler: function (after, before) {
                console.log('items',after, before)
                if(after[0].hasOwnProperty('quote_amount')){
                    this.totalPrice = after.reduce((p,n)=>{
                    console.log(p,n, parseFloat (n['quote_amount'])); return p+(!isNaN(parseFloat(n['quote_amount']))?parseFloat (n['quote_amount']):0);},0)
                }

            },
            deep: true,
        }

    },
    components: {
        'v-select': vSelect
    },
    data(){
        return {
            test:"TeSt",
            buttonText:"Wait",
            showEndActions:false,
            selectedCountry:{},
            countries:[],
            category:[],
            product_titles:[],
            note:"",
            title:"",
            selectedProduct:[],
            date:"",
            requirement:{},
            mydetails:{},
            userDetails:{},
            itemRefs:[],
            totalPrice:'0.00',
            cats:[],
            categories:'',
            canReject: true,
            canAccept: true,
            cSymbol: '$',
            items:[
                defItem
            ]
        }
    },

    async mounted(){
        // this.countriesRes = await axios.get(`search/countries/all`);
        // this.countries = this.countriesRes.data.data.map(item=> {return {"code":item.id, "label":item.name}})

        //Load Category SubCategory

        // var input = document.getElementById("dateField");
        // var today = new Date();
        // var day = today.getDate();
        // // Set month to string to add leading 0
        // var mon = new String(today.getMonth()+1); //January is 0!
        // var yr = today.getFullYear();

        //     if(mon.length < 2) { mon = "0" + mon; }

        //     var date = new String( yr + '-' + mon + '-' + day );

        // input.disabled = false;
        // input.setAttribute('min', date);


        // this.catsRes = await axios.get(`search/categories?type=1`);
        // this.cats = this.catsRes.data.data;
        console.log("am qid", )
        if(this.qid!="" && parseInt(this.qid) >0 ){
            this.showEndActions = true;
            this.requirementRes = await axios.get(`user/requirement/getRequirementQuote/${this.id}/${this.qid}`);
        }
        else{
            this.requirementRes = await axios.get(`user/requirement/getRequirement/${this.id}`);
        }

        this.requirement = this.requirementRes.data.data.requirement;
        this.items = this.requirementRes.data.data.items;
        this.mydetails = this.requirementRes.data.data.my_detials;
        this.userDetails = this.requirementRes.data.data.u_details;
        this.categories = this.requirementRes.data.data.categories.map(item=>item.title).join(',');
        this.settings = this.requirementRes.data.data.settings;
        this.cSymbol = this.settings.filter(item=>item.title=='currency_symbol')[0]['value'];


        if(Array.isArray(this.requirement.myquote) && this.requirement.myquote.length){

            this.items.forEach((item, index)=>{
                console.log('this.requirement.myquote[0].quote',parseInt(item.id),index,this.requirement.myquote[0].quote[parseInt(item.id)-1])
                // item.quote_quantity = this.requirement?.myquote?.[0]?.quote?.[index]?.quote_quantity
                item.quote_amount = this.requirement?.myquote?.[0]?.quote?.[index]?.quote_amount
            })
            this.buttonText = ''
            if(this.requirement.myquote[0]['status']==2){
                this.canAccept = false;
            }
            if(this.requirement.myquote[0]['status']==1){
                this.buttonText = 'Accept & Send Quote'
                this.canReject = true;
                if(this.userDetails.user_role.user_type_id==2){
                    this.canAccept = true;
                    this.canReject = true;
                }
                else{
                    this.canAccept = false;
                    this.canReject = false;
                }

            }
            if(this.requirement.myquote[0]['status']!=2 && this.requirement.myquote[0]['status']!=1){

                this.canReject = false;

            }
            else{
                this.buttonText = 'Send/Update Quote'
                this.canReject = false;
                this.canAccept = false;
                if(this.userDetails.user_role.user_type_id==2){
                    this.buttonText = ''
                    this.canReject = true;
                    this.canAccept = true;
                }


                this.totalPrice = this.requirement.myquote[0]['quote'].reduce((p,n)=>p+parseFloat(n.quote_amount),0)

            }
            if(this.requirement.myquote[0]['status']>=3){
                this.canReject = false;
                this.canAccept = false;
            }
        }
        else{
            this.buttonText = 'Send Quote';
        }


        // this.product_titles_res = await axios.get(`search/product_titles/all`);
        // this.product_titles = this.product_titles_res.data.data.map(item=> {return {"code":item.id, "label":item.title}})

        console.log("requirement id", this.requirementRes.data)



    },
    methods:{
        statusData(status){
            switch (status) {
                case -1:
                    return '--'
                    break;
                case 0:
                    return 'Pending'
                    break;
                case 1:
                    return 'Approved'
                    break;
                case 2:
                    return 'Rejected'
                    break;
                case 3:
                    return 'Revised'
                    break;

                default:
                    break;
            }
        },
        addAnotherRow(){
            defItem= {...defItem, id: this.items.length + 1};

            this.items.push(defItem)
        },
        setItemRef(el) {
            if (el) {
                this.itemRefs.push(el)
            }
        },
        beforeUpdate() {
            this.itemRefs = []
        },
        updated() {
            console.log('updated-->',this.itemRefs)
        },

        async  acceptQuote(sqid){

        let response = await fetch(baseURL+"user/acceptItemQuote/"+sqid)
        let resData = await response.json();
        console.log(resData)
        if(resData.type=="success"){
                location.reload();
            }
        },

        async  rejectQuote(sqid){

        if(confirm("Are you sure?")){
            let response = await fetch(baseURL+"user/rejectItemQuote/"+sqid)
            let resData = await response.json();
            console.log(resData)
            if(resData.type=="success"){
                //UPDATE STATUS AND ACTION BUTTONS
                location.reload();
            }
        }
        },
        saveRequirement(){
            console.log("Save Requirement Just Called");
            console.log(this.category, this.name, this.date, this.items, this.note);
            let dataToPush = {
                category:this.category,
                title: this.title,
                date:this.date,
                items:this.items,
                note:this.note
            }
            axios.post('user/requirement/create',dataToPush)
        },
        async approveQuote(qid){
            console.log("Approving Quote", qid);
            let dataToPush = {
                qid:this.qid,

            }
            try{
                let res = await axios.post('user/requirement/approveQuote',dataToPush)
                location.reload();
            }
            catch(err){
                console.log('got err-->', err)
            }

        },

        // async rejectQuote(qid){
        //     let qid = this.requirement?.myquote?.[0]?.id
        //     console.log("rejectQuote Quote", qid);
        //     let dataToPush = {
        //         qid,

        //     }
        //     try{
        //         let res = await axios.post('user/requirement/rejectQuote',dataToPush)
        //         //location.reload();
        //     }
        //     catch(err){
        //         console.log('got err-->', err)
        //     }

        // },
        updateItemArr(e, item){
            let indx = this.items.indexOf(item);
            //console.log('index',)
            //this.items.forEach(item=>{ console.log('am item',item); this.totalPrice += parseFloat(item.quote_amount)});
            this.items[indx][e.target.name] = e.target.value
            //item[e.target.name] = e.target.value
            this.totalPrice = 0;
            console.log('e',e.target.name,e.target.value, item);


            //this.items.reduce((p,n)=>{ console.log(p,n, parseFloat (n['quote_amount'])); return p+parseFloat(n['quote_amount']);},0)
        },
        // updateProduct(e, item){
        //     if(!e.hasOwnProperty('code')){
        //         //Need to create this product

        //     }
        //     console.log('e',e, item);
        // },

        async updateProduct(e, item){
            if(!e.hasOwnProperty('code')){
                let title = e;
                if(e.hasOwnProperty('label')){
                    title = e['label'];
                }
                //Need to create this product
                console.log('New Product Detected-?',e, item);

                let productTitleResponse = await axios.post('user/catalog/createProductTitle', {'title':title});
                // this.title = productTitleResponse.data.id;
                this.selectedProduct[(item.id)-1] = {code: productTitleResponse.data.id, label: productTitleResponse.data.title}
                item["code"] = productTitleResponse.data.id
                item["title"] = productTitleResponse.data.title
                console.log("productTitleResponse~>", productTitleResponse);
            }
            else{
                this.title = e['code']
                this.selectedProduct[(item.id)-1] = {code: e['code'], label: e['label']}
                item["code"] = e['code']
                item["title"] = e['label']
            }
            console.log("updatedItem = ",item);
        },

        removeMe(item){
            this.items = this.items
                .filter(itm => itm.id != item.id)
                .map((itm, i) => {itm.id = i+1; return itm;} )
                //.map(itm => { itm.id = this.items.findIndex(_itm=>_itm.id==itm.id)+1; return itm; })
        },

        async saveQuote(){
            console.log(this.itemRefs);
            let dataToPush = {
                rid:this.id,
                items:this.items,
            }
            console.log('dataToPush',dataToPush)
            try{
                let res = await axios.post('user/invite/updateRequirement',dataToPush)
                location.reload();
            }
            catch(err){
                console.log('got err-->', err)
            }

        }


    }
}
export default AddRequirement;
</script>
