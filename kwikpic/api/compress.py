
import os
import sys

import face_recognition

f=open("out1","w")
# Load the jpg files into numpy arrays
# print(sys.argv[1]+os.listdir(sys.argv[1])[0])
# sys.argv[1]+os.listdir(sys.argv[1])[0]
client_image = face_recognition.load_image_file(sys.argv[1]+os.listdir(sys.argv[1])[0])
# obama_image = face_recognition.load_image_file("obama.jpg")

data_list = os.listdir(sys.argv[2])
unknown_images = [face_recognition.load_image_file(sys.argv[2]+x) for x in data_list]
# print([x for x in data_list])
client_face_encoding = face_recognition.face_encodings(client_image)[0]
known_faces = [client_face_encoding]
# print("here")

# Get the face encodings for each face in each image file
for i in range(len(unknown_images)):
	unknown_image = unknown_images[i]	
	try:
	    # biden_face_encoding = face_recognition.face_encodings(biden_image)[0]
	    # obama_face_encoding = face_recognition.face_encodings(obama_image)[0]
	    unknown_face_encodings = face_recognition.face_encodings(unknown_image)
	except IndexError:
	    # print("I wasn't able to locate any faces in at least one of the images. Check the image files. Aborting...")
	    # quit()
	    continue
	
	for unknown_face_encoding in unknown_face_encodings:
		results = face_recognition.compare_faces(known_faces, unknown_face_encoding)
		if results[0]:
			f.write(sys.argv[2]+data_list[i]+"\n")
			break
f.close()
# results is an array of True/False telling if the unknown face matched anyone in the known_faces array

# print("Is the unknown face a picture of Biden? {}".format(results[0]))
# print("Is the unknown face a picture of Obama? {}".format(results[1]))
# print("Is the unknown face a new person that we've never seen before? {}".format(not True in results))


f=open("out1","r")
lines=f.readlines()
lines=[line.strip() for line in lines]
files=""
for line in lines:
	# print(line)
	files = files + " " + line
# print("zip "+sys.argv[1]+"pics.zip"+files)
os.system("zip -j "+sys.argv[3]+"pics.zip"+files)
print("done")
