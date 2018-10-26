<template>
  <div class="hello">
    <!-- <h1>{{ msg }}</h1>
    <table>
        <tr v-for="(item, index) in list" :key="index">
            <td>{{index}}</td>
            <td>{{item.username}}</td>
            <td>{{item.password}}</td>
        </tr>
    </table> -->
  </div>
</template>

<script>
import axios from 'axios';
let helloData = {
    list : []
};
export default {
  name: 'HelloWorld',
  data () {
    return helloData
  },
  mounted() {
      axios({
          url: 'getdata.action',
          headers: {'X-Requested-With':'XMLHttpRequest'}
      }).then(result =>{
          helloData.list = result.data;
      }).catch(error =>{
          alert(JSON.stringify(error));
      });
  }
}
</script>