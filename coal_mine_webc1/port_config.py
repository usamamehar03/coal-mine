import serial
import serial.tools.list_ports
from time import sleep
import sys
import json
import ast
connection="false"
def find_port():
	ports = list(serial.tools.list_ports.comports())
	for p in ports:
		name=p.description
		if  "Arduino" in name :
			return p ,True
		else:
			msg=[connection,"sorry device not found please fix ur device connection and try again"]
			print(json.dumps(msg))
			exit()
	return False ,False
def connect_port(port):
	rate= sys.argv[1]
	try:
		ser = serial.Serial(port.device,rate,timeout=10)
		try:
			ser.isOpen()
			return ser,True
		except:
			msg=[connection,"Connection error"]
			print(json.dumps(msg))
			exit()
	except:
		# msg=[connection,"Connection failed! This port already open in other process"]
		# print(json.dumps(msg))
		exit()
def read_port(serial):
	dat=serial.readline().decode()
	if len(dat)<1:
		msg=[connection,0,(port.device),(port.description)]
		print(json.dumps(msg))
		serial.close()
	else:
		dat= dat.replace('\n','')
		dat= dat.replace('\r','')
		dat = dat.split(",")
		msg=[connection,dat,(port.device),(port.description)]
		print(json.dumps(msg))
		serial.close()

						#main here
if __name__=="__main__":

	port, ckp =find_port()
	if ckp == False :
		msg=[connection,"still receiver not connected please connect device"]
		print(json.dumps(msg))
		exit()
	else:
		# print(port.device)
		# print(port.description)
		serial,cks =connect_port(port)
		if cks == True :
			# print("we connected" + '\t')
			# print('Connect ' + serial.name)
			sleep(5)
			connection="true"
			read_port(serial)
			exit()		
