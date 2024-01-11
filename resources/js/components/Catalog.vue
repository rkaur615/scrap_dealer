<template>
    <section class="wrapperMain">
		<div class="container">

			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="createReceiptForm form-wrapper h-100">
						<div class="headerForm">
							<h1 class="text-start">{{mode}} Product</h1>
						</div>
                        <form method="POST" @submit.prevent="saveRequirement">
                            <!-- main form -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="formGroup">
                                        <label for="requirementName" class="form-label">Product Name</label>
                                        <v-select taggable  v-model="selectedProduct" @option:selected="updateProductTitle($event)" :options="product_titles"></v-select>
                                        <!-- <input v-model="title" required type="text" class="form-control" id="requirementName"> -->
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                        <div class="formGroup">
                                            <label for="category" class="form-label">Category</label>
                                            <select v-model="category" required name="services" id="services" class="form-control">
                                                <template v-for="cat in categories" :key="cat.id">
                                                    <option v-if="!cat.parent_id" disabled :value="cat.id" >{{cat.title}}</option>
                                                    <option v-else :value="cat.id" >{{cat.title}}</option>
                                                </template>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-6">
                                        <div class="formGroup">
                                            <label for="category" class="form-label">Quantity</label>
                                            <input v-model="quantity" required type="number" class="form-control" id="quantity">
                                        </div>
                                    </div>





                                     <div class="col-md-6 col-6">
                                        <div class="formGroup">
                                            <label for="category" class="form-label">Unit</label>
                                            <select v-model="unit" name="unit" class="form-control">
                                                <option :value="'KG'">kg</option>
                                                <option :value="'GRAM'">gram</option>
                                                <option :value="'LITER'">ltr</option>
                                                <option :value="'UNIT'">unit(s)</option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="col-md-6 col-6">
                                        <div class="formGroup">
                                            <label for="category" class="form-label">Price</label>
                                            <input v-model="price" required type="number" class="form-control" id="price">
                                        </div>
                                    </div>

                                    <div class="col-md-12" v-if="hasImage()">
                                        <div class="formGroup" v-for="file in files" :key="file.id">
                                            <label for="aboutUs" class="form-label">Files</label>
                                            <div class="w-100 d-none d-md-block"></div>
                                            <div >

                                                <img style="height:100px; width:100px;" :src="'/storage/'+file['filepath']" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="formGroup">
                                            <label for="aboutUs" class="form-label">Upload Image</label>
                                            <div class="w-100 d-none d-md-block"></div>
                                            <input type="file" @change="upload" id="uploads" multiple="multiple" class="btn btn-success" >
                                        </div>
                                    </div>
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
import axios from '../axioslib';
import vSelect from 'vue-select'
let defItem = {
                    id: 1,
                    title:"",
                    quantity:0,
                    unit:'KG'

                };
const AddRequirement = {
    props: ["cid"],
    computed:{},
    components: {
        'v-select': vSelect
    },
    data(){
        return {
            mode:"Add",
            selectedCountry:{},
            catalog:{},
            _cid:0,
            countries:[],
            category:[],
            product_titles:[],
            selectedProduct:{},
            quantity:"",
            title:"",
            unit:"",
            price:"",
            categories:[],
            files:[],

        }
    },

    async mounted(){
        if(this.cid && this.cid>0){
            this.mode = "Edit";
        }
        // this.countriesRes = await axios.get(`search/countries/all`);
        // this.countries = this.countriesRes.data.data.map(item=> {return {"code":item.id, "label":item.name}})

        //Load Category SubCategory

        this.product_titles_res = await axios.get(`search/product_titles/all`);
        this.product_titles = this.product_titles_res.data.data.map(item=> {return {"code":item.id, "label":item.title}})

        this.categoriesRes = await axios.get(`search/categoriesForProduct?type=1&tm=${1000*Math.random(1000)+1}`);
        this.categories = this.categoriesRes.data.data;

        if(this.cid && this.cid>0){

            this.catalogRes = await axios.get(`user/catalog/getSingle/${this.cid}`);
            this.catalog = this.catalogRes.data;
            this.category = this.catalog.cat.id;
            this.quantity = this.catalog.quantity;
            this.unit = this.catalog.unit;
            this.price = this.catalog.price;
            this.files = this.catalog.images;

            console.log("Catalog", this.catalog);
            this.title = this.catalog.title.id;
            this.selectedProduct = {code:this.catalog.title.id, label:this.catalog.title.title};
        }

        console.log("this.categories-->",this.categories);
    },

    methods:{
        addAnotherRow(){
            defItem= {...defItem, id: this.items.length + 1};

            this.items.push(defItem)
        },
        async saveRequirement(){
            console.log("Save Requirement Just Called");
            console.log(this.category, this.name, this.date, this.items, this.note);
            let dataToPush = {
                cid: this.cid || 0,
                category:this.category,
                title: this.title,
                price: this.price,
                unit: this.unit,
                quantity: this.quantity
            }
            let product = await axios.post('user/catalog/create',dataToPush)
            this._cid = product.data.id;
            console.log("here is product data", product);
            location.href = product.data.to;

        },
        updateItemArr(e, item){

            item[e.target.name] = e.target.value
            console.log('e',e.target.name,e.target.value, item);
        },
        updateProduct(e, item){
            if(!e.hasOwnProperty('code')){
                //Need to create this product

            }
            console.log('e',e, item);
        },

        async updateProductTitle(e, item){
            if(!e.hasOwnProperty('code')){
                let title = e;
                if(e.hasOwnProperty('label')){
                    title = e['label'];
                }
                //Need to create this product
                console.log('New Product Detected-?',e, item);

                let productTitleResponse = await axios.post('user/catalog/createProductTitle', {'title':title});
                this.title = productTitleResponse.data.id;
                console.log("productTitleResponse~>", productTitleResponse);
            }
            else{
                this.title = e['code']
            }

        },

        hasImage(){
            return this.files.length;
        },


        async upload(){
            console.log('uploads Called');
            var formData = new FormData();
            formData.append("filetype", 2);
            formData.append("ref_id", this.cid || this._cid );

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

    }
}
export default AddRequirement;
</script>
