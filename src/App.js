import "bootstrap/dist/css/bootstrap.min.css"
import { BrowserRouter, Routes, Route } from "react-router-dom"
import PostsAndLikes from "./Contan/PostsAndLikes"
import Like from "./Components/Like"
import Posts from "./Components/Posts"
import TheLogin from "./Components/theLogin"
import Home from "./Components/Home"


function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<TheLogin />} />
        <Route path="/home" element={<Home />} />
        <Route path="/posts" element={<PostsAndLikes /> } />
      </Routes>
    </BrowserRouter>
  )
}

export default App