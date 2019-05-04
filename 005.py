#!/usr/bin/python
import nmap
import sys
import requests
import random
import ipwhois
import _thread
import time


thread_count = 10


def scan():
	foundone = False

	while foundone == False:
		if len(sys.argv) > 1:
			ip = sys.argv[1]
			foundone = True
		else:
			validated = False
			while validated == False:
				ip = str(random.randint(0, 255))+"."+str(random.randint(0, 255))+"."+str(random.randint(0, 255))+"."+str(random.randint(0, 255))
				address = ip.split(".")
				block = address[0]+"."+address[1]+"."+address[2]+".0/24"				
				try:
					ipwhois.IPWhois(ip)
					validated = True
				except Exception:
					pass
		print("Using ip range: "+block)
		nm = nmap.PortScanner()
		nm.scan(block, arguments='-F')
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
	
		for a in range(0, len(hostgrouped), 3):
			ipw = ipwhois.IPWhois(hostgrouped[a])
			result = ipw.lookup_rdap()
			hostgrouped[a+2].append(result)

		if len(hostgrouped) > 0:
			print("")
			for a in range(0, len(hostgrouped), 3) :
				print(str(hostgrouped[a]))
				print("\n".join(hostgrouped[a+1]))
				print(str(hostgrouped[a+2]))
				print("----------")
			print(block+" done.")
			a = input("Find more [Y/n]? ") or "y"
			if("n" in a or "N" in a):
				foundone = True
		else:
			print(block+" no active hosts found.\n")

	
for a in range(0, thread_count):
	time.sleep(1)
	_thread.start_new_thread(scan, ())

while 1:
	pass
