#!/usr/bin/env python
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
			msg=[connection,"sorry device not found plz fix your device connection and try again"]
			print(json.dumps(msg))
			exite()
	return False ,False
def connect_port(port):
	rate= sys.argv[1]
	ser = serial.Serial(port.device,rate,timeout=30)
	try:
		ser.isOpen()
		return ser,True
	except:
		msg=[connection,"sorry we got problem to connect"]
		serial.close()
		print(json.dumps(msg))
		exite()

						#main here
if __name__=="__main__":

	port, ckp =find_port()
	if ckp == False :
		msg=[connection,"still drone reciever not connected plz connect device"]
		print(json.dumps(msg))
		exite()
	else:
		# print(port.device)
		# print(port.description)
		serial,cks =connect_port(port)
		if cks == True :
			connection="true"
			msg=[connection,"","plz wait we are connecting  reciever......"
			,"Be patinet connection setup will take a while","new device  "+ (port.description)+" found"
			,"we are connecting device at "+(port.device),"connection successfull"
			,(port.description),(port.device)]
			print(json.dumps(msg))
			exite()		