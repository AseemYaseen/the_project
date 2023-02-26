import React from 'react'
import { useEffect, useState} from 'react'
import axios from 'axios';
import { useNavigate } from 'react-router-dom';


const TheLogin = () => {
   const navigate = useNavigate()
    const [inputs, setInputs]=useState({}); 
    const [users, setUsers]=useState([]);

    useEffect(()=>{
        getUsers();
    }, []);


    const handleChange= (event)=>{

        const name = event.target.name; // to get the name of the input
        const value = event.target.value; // to get the value of the input 
        
        setInputs(values =>({...values, [name]: value})); // to set the values (the name of input then : the value of that input) to values
    }  

    const handleSubmit =(event)=>{
        event.preventDefault(); // to prevent the page from refresh on submit

        // console.log(inputs, "My inputs")
    }

    const getUsers = ()=>{

        axios.get('http://localhost:80/React/Project_Backend/theUsers.php/users').then((response)=>{
            // console.log(response.data); // to print the data from database in console
            setUsers(response.data); // add data to my useState
            console.log(response.data)
        
        })
     }


    const login = () => {
        for (const user of users) {
            console.log(inputs)
            console.log(users)
          if (user.email === inputs.email && user.password === inputs.password && Object.entries(inputs.email).length > 0 && Object.entries(inputs.password).length > 0) {
            console.log("Welcome User");
            localStorage.setItem('id' , JSON.stringify(user.id))
            localStorage.setItem('first_name' , JSON.stringify(user.first_name))
            localStorage.setItem('email' , JSON.stringify( user.email))
            navigate('/home')

            return; // Exit the loop once a matching user is found
          }
        }
        document.getElementById('errorLog').innerHTML = "the password or email is wrong"
        console.log("Not User !");
      }

 

  return (

    <div>
         <div>
        <h1>Login</h1>
        <form onSubmit={handleSubmit}> {/* to prevent the page from refresh on submit */}
        <label>Email:</label>
        <input value={inputs.email} type="text" name="email" onChange={handleChange}/> {/* send data to handleSubmit function , to get the name and the value */}
        <br/>
        <label>Password:</label>
        <input value={inputs.password} type="text" name="password" onChange={handleChange}/>
        <br/>
        <button onClick={login} type='submit'>Log In</button>
        <p id='errorLog'></p>
        </form>

    </div>
    </div>
  )
}

export default TheLogin