import "../css/style.scss"

// Our modules / classes
import MobileMenu from "./modules/MobileMenu"
import HeroSlider from "./modules/HeroSlider"
// import GoogleMap from "./modules/GoogleMap"  //使用谷歌地图，文件时ACF自定义字段 文档提供的
import Search from "./modules/Search"
import MyNotes from "./modules/MyNotes"
import Like from "./modules/Like"

// Instantiate a new object using our modules/classes
const mobileMenu = new MobileMenu()
const heroSlider = new HeroSlider()
// const googleMap = new GoogleMap()
const search = new Search()
const myNotes = new MyNotes()
const like = new Like()