

export default {
async listsproductsAction (context){
 await axios.get('/data-products').then(response=>{
    localStorage.setItem('listsProducts',JSON.stringify(response.data))
    context.commit('listsproductsMuttation',{
      listsProducts: response.data
    })
  }).catch()
},
async tryListsproductsAction (context){

   const products = localStorage.getItem('listsProducts');
    context.commit('listsproductsMuttation',{
      listsProducts: JSON.parse(products)
    })

}
}
