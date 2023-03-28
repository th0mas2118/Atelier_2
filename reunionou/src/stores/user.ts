import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import router from '../router/index.js'
import axios from 'axios'

export const useUserStore = defineStore(
  'user',
  () => {
    const isConnected = ref(false)
    const member = reactive({
      email: '',
      id:'',
      username:'',
      level:0,
      acces_token:'',
    })

    const parseJwt = (token : string) => {
      try {
        return JSON.parse(atob(token.split('.')[1]));
      } catch (e) {
        return null;
      }
    };

    async function setConnected(log : {email: string, password: string}){
      await fetch('http://api.frontoffice.reunionou:49383/signin',{
        method: 'POST',
        mode:"cors",
        headers: {
          "Authorization": `Basic ${btoa(`${log.email}:${log.password}`)}`
        }
      }).then((response)=>{
        if(response.status!=201){
          console.log(response.status)
          throw new Error("Erreur de connexion")
        }else{
          return response.json()
        }
      })
      .then((response)=>{
        const decodedToken = parseJwt(response.token)
        member.email = decodedToken.usermail
        member.id = decodedToken.uid
        member.username = decodedToken.username
        member.level = decodedToken.lvl
        member.acces_token= response.token
        isConnected.value = true
      })
    }

    async function disconnect(){
      await fetch('http://api.frontoffice.reunionou:49383/signout',{
        method:'POST',
        mode:"cors",
        headers: {
          "Authorization": `Bearer ${member.acces_token}`
      }}).then((response)=>isConnected.value = false)
    }
  
    return { isConnected, setConnected,disconnect, member }
  }
)