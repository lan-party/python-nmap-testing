#!/usr/bin/python
import nmap
import sys
import requests
import random

foundone = False

while foundone == False:
	if len(sys.argv) > 1:
		ip = sys.argv[1]
		foundone = True
	else:
		validated = False
		while validated == False:
			ip = str(random.randint(0, 255))+"."+str(random.randint(0, 255))+"."+str(random.randint(0, 255))+"."+str(random.randint(0, 255))
			ipr = requests.get('http://ip-api.com/json/'+ip).json()
			if "reserved range" not in str(ipr):
				validated = True

	print("Using ip: "+ip)
	print(ipr)
	address = ip.split(".")

	print("Running nmap...")
	nm = nmap.PortScanner()
	nm.scan(address[0]+"."+address[1]+"."+address[2]+".0/24", arguments='-F')
	nm.command_line()
	nm.scaninfo()
	nm.all_hosts()
	results = nm.csv().splitlines()
	hostgrouped = []

	for a in range(0, len(results)):
		resultsplit = results[a].split(';')
		if resultsplit[0] != 'host':
			if resultsplit[0] not in hostgrouped:
				hostgrouped.append(resultsplit[0])
				hostgrouped.append([])
				hostgrouped.append([])
			hostgrouped[hostgrouped.index(resultsplit[0])+1].append(results[a])

	print("Runnning host lookup...")
	for a in range(0, len(hostgrouped), 3):
		result = requests.get('http://ip-api.com/json/'+hostgrouped[a])
		hostgrouped[a+2].append(result.json())

	if len(hostgrouped) > 0:
		print("")
		for a in range(0, len(hostgrouped), 3) :
			print(str(hostgrouped[a]))
			print("\n".join(hostgrouped[a+1]))
			print(str(hostgrouped[a+2]))
			print("----------")
		a = input("Find more [Y/n]? ") or "y"
		if("n" in a or "N" in a):
			foundone = True
	else:
		print("No active hosts found\n")

