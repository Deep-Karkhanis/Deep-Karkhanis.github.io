const videoPlayer = document.querySelector("#player");
const videoPlayer2 = document.querySelector("#player2");
const canvasElement = document.querySelector("#canvas");
// const canvasParent = document.querySelector("#canvas_parent");
const captureButton = document.querySelector("#capture-btn");
const clickButton = document.querySelector("#click-btn");
const imagePicker = document.querySelector("#image-picker");
const imagePickerArea = document.querySelector("#pick-image");
const newImages = document.querySelector("#newImages");
const email_box = document.querySelector("#email");
const name_box = document.querySelector("#name");

// Image dimensions
// const width = videoPlayer.offsetWidth;
// const height = videoPlayer.offsetHeight;
let zIndex = 1;

const createImage = (src, alt, title, width, height, className) => {
  let newImg = document.createElement("img");

  if (src !== null) newImg.setAttribute("src", src);
  if (alt !== null) newImg.setAttribute("alt", alt);
  if (title !== null) newImg.setAttribute("title", title);
  if (width !== null) newImg.setAttribute("width", width);
  if (height !== null) newImg.setAttribute("height", height);
  if (className !== null) newImg.setAttribute("class", className);

  return newImg;
};

const startMedia = () => {
  if (!("mediaDevices" in navigator)) {
    navigator.mediaDevices = {};
  }

  if (!("getUserMedia" in navigator.mediaDevices)) {
    navigator.mediaDevices.getUserMedia = constraints => {
      const getUserMedia =
        navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

      if (!getUserMedia) {
        return Promise.reject(new Error("getUserMedia is not supported"));
      } else {
        return new Promise((resolve, reject) =>
          getUserMedia.call(navigator, constraints, resolve, reject)
        );
      }
    };
  }

  navigator.mediaDevices
    .getUserMedia({ video: true })
    .then(stream => {
      videoPlayer.srcObject = stream;
      videoPlayer.style.display = "block";
    })
    .catch(err => {
      imagePickerArea.style.display = "block";
    });
};

const resizeCanvas = () => {
  // console.log("Resizing")
  // canvasParent.style.height = videoPlayer.offsetHeight + 'px'
  canvasElement.style.height = videoPlayer.offsetHeight + 'px'
  canvasElement.style.width = videoPlayer.offsetWidth + 'px'
  canvasElement.width = videoPlayer.offsetWidth
  canvasElement.height = videoPlayer.offsetHeight


};

// Capture the image, save it and then paste it to the DOM
clickButton.addEventListener("click", event => {
  // Draw the image from the video player on the canvas
  
  canvasElement.style.display = "block";
  const context = canvasElement.getContext("2d");
  // context.drawImage(videoPlayer, 0, 0, videoPlayer.offsetWidth, videoPlayer.offsetHeight);
  // canvasElement.height = videoPlayer.offsetHeight
  // canvasParent.style.height = videoPlayer.offsetHeight + 'px'
  canvasElement.style.height = videoPlayer.offsetHeight + 'px'
  canvasElement.style.width = videoPlayer.offsetWidth + 'px'
  canvasElement.width = videoPlayer.offsetWidth
  canvasElement.height = videoPlayer.offsetHeight

  context.drawImage(videoPlayer, 0, 0, videoPlayer.offsetWidth,videoPlayer.offsetHeight);
  // console.log(videoPlayer.videoWidth)
  // console.log(videoPlayer.videoHeight)
  // canvasElement.style.width = videoPlayer.offsetWidth + 'px'
  // console.log(videoPlayer.offsetHeight)
  console.log(canvasElement.offsetWidth)
  console.log(canvasElement.offsetHeight)
  console.log(canvasParent.style.width)
  console.log(canvasParent.style.height)
  console.log(canvasElement.style.width)
  console.log(canvasElement.style.height)
  console.log(videoPlayer.offsetWidth)
  console.log(videoPlayer.offsetHeight)
  // console.log("hehe")
  // videoPlayer2.srcObject.getVideoTracks().forEach(track => {
  //   track.stop();
  // });

  // Convert the data so it can be saved as a file
  let picture = canvasElement.toDataURL();

  // Save the file by posting it to the server
  fetch("./api/click_image.php", {
    method: "post",
    body: JSON.stringify({ data: picture , name: name_box.value, email: email_box.value})
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {

        console.log(data.ls);

        // Creatstyle.he the image and give it the CSS style with a random tilt
        //  and a z-index which gets bigger
        //  each time that an image is added to the div
        let newImage = createImage(
          data.path,
          "new image",
          "new image",
          videoPlayer.offsetWidth,
          videoPlayer.offsetHeight,
          "masked"
        );
        console.log(newImage);
        let tilt = (10 + 20 * Math.random());
        newImage.style.transform = "rotate(" + tilt + "deg)";
        zIndex++;
        newImage.style.zIndex = zIndex;
        // newImages.appendChild(newImage);
        // canvasElement.classList.add("masked");
      }
      else{
        console.log("failed")
        console.log(data.reason)
      }
    })
    .catch(error => console.log(error));
  // window.location = "./api/save_image.php"
});

// Capture the image, save it and then paste it to the DOM
captureButton.addEventListener("click", event => {
  // Draw the image from the video player on the canvas
  // canvasElement.style.display = "block";
  // const context = canvasElement.getContext("2d");
  // context.drawImage(videoPlayer, 0, 0, canvas.width, canvas.height);

  videoPlayer.srcObject.getVideoTracks().forEach(track => {
    // track.stop();
  });

  // Convert the data so it can be saved as a file
  // let picture = canvasElement.toDataURL("image/jpeg");

  // alert("Thank You for Using KwikPic. \n");
  document.getElementById("post_sub").innerHTML = 
    "<br>Thank You for Using KwikPic<br>Your photos will be mailed to you in a few minutes...<br>";

  // Save the file by posting it to the server
  fetch("./api/save_image.php", {
    method: "post",
    body: JSON.stringify({ data: "picture" , name: name_box.value, email: email_box.value})
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {

        console.log(data.ls);

        // Create the image and give it the CSS style with a random tilt
        //  and a z-index which gets bigger
        //  each time that an image is added to the div
        // let newImage = createImage(
        //   data.path,
        //   "new image",
        //   "new image",
        //   width,
        //   height,
        //   "masked"
        // );
        // console.log(newImage);
        // let tilt = (10 + 20 * Math.random());
        // newImage.style.transform = "rotate(" + tilt + "deg)";
        // zIndex++;
        // newImage.style.zIndex = zIndex;
        // newImages.appendChild(newImage);
        // canvasElement.classList.add("masked");
      }
      else{
        console.log("failed")
        console.log(data.reason)
      }
    })
    .catch(error => console.log(error));
  // window.location = "./api/save_image.php"
});



window.addEventListener("load", event => startMedia());
window.addEventListener("resize", event => resizeCanvas());
