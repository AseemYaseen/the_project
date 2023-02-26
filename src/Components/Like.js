import axios from 'axios'
import React, { useEffect, useState } from 'react'
import { Button, Card, FloatingLabel, Form } from 'react-bootstrap';
import { Link } from 'react-router-dom';


const Like = () => {

  const [likes, setLikes] = useState([]);
  const [readLikes, setReadLikes] = useState([]);
  const [readPosts, setReadPosts] = useState([]);


  useEffect(() => {
    getPosts();
    getLikes();
  }, []);

  const userName = localStorage.getItem('name');
  const userID = localStorage.getItem('id');

  const getPosts = () => {
    axios.get('http://localhost/React/Project_Backend/Posts.php').then(function (response) {
      setReadPosts(response.data);
    })
  }

  const getLikes = () => {
    axios.get('http://localhost/React/Project_Backend/Like.php').then(function (response) {
      setReadLikes(response.data);
      
    })
  }
  console.log(readLikes);

  const likePost = (postId) => {
    const existingLike = Array.isArray(readLikes) ? readLikes.find((like) => like.user_id === userID && like.post_id === postId) : null;
    if (existingLike) {
        console.log("responce delete");

      axios.delete(`http://localhost/React/Project_Backend/Like.php/${readLikes.id}/delete`).then(function (response) {
        setLikes(likes.filter((like) => like.post_id !== postId));
        setReadLikes(readLikes.filter((like) => like.id !== readLikes.id));
        
      });
let likePost= document.getElementById("likePost");
        console.log(likePost,"test");
    } else {

      axios.post('http://localhost/React/Project_Backend/Like.php', {
        user_id: userID,
        post_id: postId
      }).then(function (response) {
        console.log(response.data);
        setLikes([...likes, response.data]);
        setReadLikes([...readLikes, response.data]);
      });
    }
  }

  const deletePost = (id) => {
    const confirmDelete = window.confirm("Are you sure you want to delete this post?");
    if (confirmDelete) {
      axios.delete(`http://localhost/React/Project_Backend/Posts.php/${id}/delete`).then(function (response) {
        console.log(response)
        getPosts();
      })
    }
  }

  const isLiked = (postId) => {
    if (Array.isArray(readLikes) && readLikes.length > 0) {
      return readLikes.some((para) => para.user_id === userID && para.post_id === postId);
    }else{
    return false;
  }
}

const isNotLiked = (postId) => {
  if (Array.isArray(readLikes) && readLikes.length > 0) {
    return readLikes.some((para) => para.user_id === userID && para.post_id === postId);
  }else{
  return false;
}
}

  return (
    <div>
      <Card.Header>All Posts</Card.Header>
      {readPosts.map((post, key) =>
        <Card key={key}>
          <Card.Body>
            <Card.Title>{post.first_name}{" "}{post.last_name} {<p style={{ fontSize: "14px" }}>has posted</p>}</Card.Title>
            <Card.Text>{post.content}</Card.Text>

            {/* <Button variant="secondary" onClick={() => likePost(post.posts_id)} disabled={isLiked(post.posts_id)}>
              {isLiked(post.posts_id) ? 'Liked' : 'Like'}
            </Button> */}
            {(readLikes.user_id == userID && readLikes.post_id == post.post_id)?
              <button id="likePost" onClick={() => likePost(post.posts_id)}>
                Liked
              </button>
              :
              <button onClick={() => likePost(post.posts_id)}>
                Like
              </button>}
            {(post.user_id == userID) ?
              <Button onClick={() => deletePost(post.posts_id)} variant="danger" type='submit'>Delete</Button> :
              null
            }
          </Card.Body>
        </Card>
      )}
    </div>
  )
}

export default Like
