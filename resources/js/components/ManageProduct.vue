<template>

<section class="wrapperMain">
    Manage Product
    {{product_titles}}
	</section>
</template>
<script>
import { onMounted, ref } from 'vue';

const ManageProduct = {
    setup(){
        let product_titles = ref([])
        onMounted(()=>{
            axios.get(`/search/product_titles/all`).then(data=>{
                product_titles = data.data.data.map(item=> {return {"code":item.id, "label":item.title}})
                console.log("that.product_titles",product_titles);
            });
        })

        return{
            product_titles,
        }
    },

}
export default ManageProduct;
</script>
