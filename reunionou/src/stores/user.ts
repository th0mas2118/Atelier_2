import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import router from '../router/index.js'

export const useUserStore = defineStore(
  'user',
  () => {
    const isConnected = ref(false)
    const member = reactive({
      email: '',
      id:'',
      username:'',
      level:0,
    })

    async function setConnected(log : {email: string, password: string}){
      await fetch('http://api.frontoffice.reunionou:49383/signin',{
        method: 'POST',
        mode:"cors",
        headers: {
          "Authorization": `Basic ${btoa(`${log.email}:${log.password}`)}`
        }
      }).then((response)=>{
        if(response.status!=201){
          throw new Error("Erreur de connexion")
        }else{
          return response.json()
        }
      })
      .then((response)=>{
        console.log(response)
        member.email = response.user.email
        member.id = response.user.id
        member.username = response.user.username
        member.level = response.user.level
        isConnected.value = true
      })
    }

    async function disconnect(){
      await fetch('http://api.frontoffice.reunionou:49383/signout',{
        method:'POST',
        mode:"cors",
        headers: {
          "Authorization": `Bearer`
      }}).then((response)=>{
        if(response.status!=20){
          throw new Error("Erreur de déconnexion")
        }else{
          return response.json()
        }
      })
    }
  
    return { isConnected, setConnected,disconnect, member }
  }
)
