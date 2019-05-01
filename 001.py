#!/usr/bin/python
import nmap
import sys
import requests

# http://ip-api.com/json/

nm = nmap.PortScanner()
nm.scan(sys.argv[1], '20-90')
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
		hostgrouped[hostgrouped.index(resultsplit[0])+1].append(results[a])

for a in range(0, len(hostgrouped), 2) :
	print(str(hostgrouped[a]))
	print(str(hostgrouped[a+1]))
	print("----------")
