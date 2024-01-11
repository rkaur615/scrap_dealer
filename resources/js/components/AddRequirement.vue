<template>
    <section class="wrapperMain">
		<div class="container">

			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="createReceiptForm form-wrapper h-100">
						<div class="headerForm">
							<h1 class="text-start">Add New Requirement</h1>
						</div>
                        <form method="POST" @submit.prevent="saveRequirement">
                            <!-- main form -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="formGroup">
                                        <label for="requirementName" class="form-label">Requirement Name</label>
                                        <input v-model="title" required type="text" class="form-control" id="requirementName">
                                    </div>
                                </div>
                                <div class="">
                                    <div class="col-md-12 col-12">
                                        <div class="formGroup">
                                            <label for="category" class="form-label">Category</label>
                                            <Multiselect
                                                v-model="category"
                                                mode="tags"
                                                :required="true"
                                                :close-on-select="true"
                                                :groups="true"
                                                :options="categories_opts"
                                                @input="changeCat"
                                                />
                                            <!-- <select v-model="category" required name="services" id="services" multiple class="form-control">
                                                <template v-for="cat in cats" :key="cat.id">
                                                    <option v-if="!cat.parent_id" disabled :value="cat.id" >{{cat.title}}</option>
                                                    <option v-else :value="cat.id" >{{cat.title}}</option>
                                                </template>

                                            </select> -->

                                        </div>
                                    </div>

                                </div>

                                <div class="itemListForm">
                                    <div class="table-responsive addRequirement">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>item Name</th>
                                                    <th>Qty.</th>
                                                    <th>Unit</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td><strong>1.</strong></td>
                                                    <td>Jacob's Baked Crinklys</td>
                                                    <td>5</td>
                                                    <td>Kg</td>
                                                    <td></td>
                                                </tr> -->

                                                <template v-for="item in items" :key="item.id">

                                                    <tr>
                                                        <td><strong>{{item.id}}.</strong></td>
                                                        <td>

                                                        <!-- <input type="text" name="title" required class="" @change="updateItemArr($event, item)"> -->
                                                        <v-select taggable v-model="selectedProduct[(item.id)-1]"  @option:selected="updateProduct($event, item)" :options="product_titles">
                                                            <template #search="{attributes, events}">
                                                                <input
                                                                class="vs__search"
                                                                pattern="([A-z0-9À-ž\s]){2,}"
                                                                :required="!selectedProduct[(item.id)-1]"
                                                                v-bind="attributes"
                                                                v-on="events"
                                                                />
                                                            </template>

                                                        </v-select>
                                                        </td>
                                                        <td><input type="text" name="quantity" :value="item.quantity" required class="qtyInput" @change="updateItemArr($event, item)"></td>
                                                        <td>
                                                            <select @change="updateItemArr($event, item)" name="unit">
                                                                <option :selected="item.unit=='KG'" :value="'KG'">kg</option>
                                                                <option :selected="item.unit=='GRAM'" :value="'GRAM'">gram</option>
                                                                <option :selected="item.unit=='LITER'" :value="'LITER'">ltr</option>
                                                                <option :selected="item.unit=='UNIT'" :value="'UNIT'">unit(s)</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <!-- && item.id==items.length -->
                                                            <button v-if="item.id > 1 " @click="removeMe(item)" class="btn btn-danger btnAdd" ><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        </td>
                                                    </tr>
                                                </template>

                                                <tr>

                                                    <td class="text-end" colspan="5"><br>
                                                        <a class="btn btn-primary btn-xs" @click="addAnotherRow" style="padding: 5px 10px;font-size: 14px;">Add New Item</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="expectedDate formGroup">
                                        <strong>Expected Delivery Date:&nbsp;&nbsp;</strong> <input type="date" class="form-control" v-model="date" :min="minDate" required style="width: 185px;display: inline-block;border: 1px solid #ccc;padding: 5px 10px;font-size: 14px;">
                                    </div>
                                </div>
                            </div>
                            <div class="formGroup">
                                <label for="additionalNote" class="form-label">Additional Note:</label>
                                <input type="text" v-model="note" required class="form-control" id="additionalNote" style="border: 1px solid #ccc;padding: 5px;">
                            </div>

                            <div class="mb-3 mt-5 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        <!-- /main form -->
                    </form>
					</div>
				</div>
			</div>


		</div>
	</section>
</template>
<script>
import axios, { baseURL } from '../axioslib';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import moment from 'moment';
import Multiselect from '@vueform/multiselect'

let defItem = {
        id: 1,
        title:"",
        quantity:0,
        unit:'KG',
        code:0

    };
const AddRequirement = {
    props: ["rid"],
    computed:{
        minDate(){
            return moment().format('YYYY-MM-DD')
        }
    },
    components: {
        'v-select': vSelect,
        Multiselect
    },
    data(){
        return {
            test:"TeSt",
            selectedCountry:{},
            countries:[],
            category:[],
            product_titles:[],
            note:"",
            title:"",
            selectedProduct:[],
            date:"",
            categories_opts:[],
            cats:[],
            items:[
                defItem
            ]
        }
    },

    async mounted(){
        if(this.rid && this.rid>0){
            this.mode = "Edit";
        }
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


        this.catsRes = await axios.get(`search/categories?type=1`);
        this.cats = this.catsRes.data.data;
        let parent_cats = this.catsRes.data.data.filter(item=>!item.parent_id);
        parent_cats.forEach((opt)=>{
            this.categories_opts.push({'label':opt.title, options:this.catsRes.data.data.filter(item=>item.parent_id==opt.id).map(item=>{ return {"value":item.id,"label":item.title}; })});
        });

        this.categories_opts.push({'label':'Other', options:[{"value":-1,"label":'Other'}] });

        //this.product_titles_res = await axios.post(`search/product_titles/all`,this.category);
        //this.product_titles = this.product_titles_res.data.data.map(item=> {return {"code":item.id, "label":item.title}})


        console.log("this.cats-->",this.cats);


        if(this.rid && this.rid>0){
            this.items = [];
            this.requirementRes = await axios.get(`user/requirement/getSingle/${this.rid}`);
            this.requirement = this.requirementRes.data;
            this.category = this.requirement.categories
            this.product_titles_res = await axios.post(`search/product_titles/selected`,{category:this.category});
            this.product_titles = this.product_titles_res.data.data.map(item=> {return {"code":item.id, "label":item.title}})
            this.date = moment(this.requirement.expected_date,'DD/MM/YYYY').format('YYYY-MM-DD');
            this.note = this.requirement.notes;
            this.title = this.requirement.title;

            this.requirement.items.forEach((_item, index)=>{
                console.log("this._item", {..._item, index});
                this.selectedProduct[index] = {code:_item.code, label:_item.title}
                // this.items.push(
                //     {
                //         id: 1,
                //         title:"Aman",
                //         quantity:10,
                //         unit:'GRAM',
                //         code:10

                //     }
                // );
                this.items.push({..._item});

            })

            console.log("this.requirement items", this.items);
            // this.category = this.requirement.cat.id;
            // this.quantity = this.requirement.quantity;
            // this.unit = this.catalog.unit;
            // this.price = this.catalog.price;
            // this.files = this.catalog.images;

            // console.log("Catalog", this.catalog);
            // this.title = this.catalog.title.id;
            // this.selectedProduct = {code:this.catalog.title.id, label:this.catalog.title.title};
        }

    },
    methods:{
        addAnotherRow(){
            defItem= {...defItem, id: this.items.length + 1, quantity:0};

            this.items.push(defItem)
        },
        async changeCat(data){
            this.product_titles_res = await axios.post(`search/product_titles/selected`,{category:data});
            this.product_titles = this.product_titles_res.data.data.map(item=> {return {"code":item.id, "label":item.title}})
            //this.selectedProduct = [];
            //console.log('Products Titles->', this.product_titles)
            this.items.forEach((_item, index)=>{
                //console.log("this._item", {..._item, index});
                if(this.product_titles.filter(_pitem=>{ console.log(index,_pitem,_item.code, _pitem.code!==_item.code);  return _pitem.code===_item.code} ).length ){

                }
                else{
                    this.selectedProduct[index] = {}
                }

                //this.items.push({..._item});

            })


        },
        async saveRequirement(){
            console.log("Save Requirement Just Called");
            console.log(this.category, this.name, this.date, this.items, this.note);
            let dataToPush = {
                category:this.category,
                title: this.title,
                date:this.date,
                items:this.items,
                note:this.note
            }
            if(this.rid && this.rid!=="0"){
                console.log(this.rid, typeof this.rid )
                try{
                    let resp = await axios.post('user/requirement/update/'+this.rid,dataToPush);
                    location.href=baseURL+"user/requirement/list"
                }
                catch (err) {
                    throw new Error('Unable to establish a login session.'); // here I'd like to send the error to the user instead
                }
            }
            else{
                try{
                    let resp = await axios.post('user/requirement/create',dataToPush);
                    location.href=baseURL+"user/requirement/list"
                }
                catch (err) {
                    throw new Error('Unable to establish a login session.'); // here I'd like to send the error to the user instead
                }

            }

            //
        },
        updateItemArr(e, item){

            item[e.target.name] = e.target.value
            console.log('e',e.target.name,e.target.value, item);
        },
        // updateProduct(e, item){
        //     if(!e.hasOwnProperty('code')){
        //         //Need to create this product

        //     }
        //     console.log('e',e, item);
        // },

        async updateProduct(e, item){
            console.log('New Product Detected-?',e, item);
            if(!e.hasOwnProperty('code')){
                // let title = e;
                // if(e.hasOwnProperty('label')){
                //     title = e['label'];
                // }
                //Need to create this product


                let productTitleResponse = await axios.post('user/catalog/createProductTitle', {'title':e.label});
                // this.title = productTitleResponse.data.id;
                this.selectedProduct[(item.id)-1] = {code: productTitleResponse.data.id, label: productTitleResponse.data.title}
                item["code"] = productTitleResponse.data.id
                item["title"] = productTitleResponse.data.title
                console.log("productTitleResponse~>", productTitleResponse);
            }
            else{
                // this.title = e['code']
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
        }
    }
}
export default AddRequirement;
</script>
