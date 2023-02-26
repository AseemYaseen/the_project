import axios from 'axios'
import React, { useEffect, useState } from 'react'
import { Button, Card, FloatingLabel, Form } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const Posts = () => {

    const [posts, setPosts]=useState([])
    
    const handleChange = (event) => {
        const name = event.target.name;
        const value = event.target.value;
        const userId = event.target.alt;
        setPosts(values => ({ ...values, [name]: value }));
      }
      
    const newPost = (e)=>{
        e.preventDefault()
        const postData ={ ...posts, userId: localStorage.getItem('id') }
           
    axios.post('http://localhost/React/Project_Backend/Posts.php', postData).then(function(response){
        console.log(response.data); 
        window.location.assign('/posts')
        // setReadPosts(response.data); 
        // console.log(readPosts);
        
    })
 }

  return (
    <div>
        <Form onSubmit={newPost}>
        <Card.Header>Write a new post</Card.Header>
      <FloatingLabel controlId="floatingTextarea2" label="Whats in your mind ?">
        <Form.Control
          as="textarea"
          name='post'
          alt='userId'
          onChange={handleChange}
          style={{ height: '100px' }}
        />
      <Button variant="primary" type='submit'>Post</Button>
      </FloatingLabel>
        </Form>
    </div>
  )
}

export default Posts