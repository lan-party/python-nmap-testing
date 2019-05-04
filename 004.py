#!/usr/bin/python
import nmap
import sys
import requests
import random
import os

foundone = False
ispcount = {}

# A fast and dirty way to visualized ISP fequency
while foundone == False:
	validated = False
	while validated == False:
		ip = str(random.randint(0, 255))+"."+str(random.randint(0, 255))+"."+str(random.randint(0, 255))+"."+str(random.randint(0, 255))
		ipr = requests.get('http://ip-api.com/json/'+ip).json()
		if "reserved range" not in str(ipr):
			validated = True
	if "isp" in ipr:
		print(ip)
		os.system("clear")
		print(ipr["isp"])
		if ipr["isp"] in ispcount:
			ispcount[ipr["isp"]] = ispcount[ipr["isp"]] + 1
		else:
			ispcount[ipr["isp"]] = 1
		print(ispcount)
		#print("------------")
