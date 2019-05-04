#!/usr/bin/python
import nmap
import sys
import requests

print("Running nmap...")
nm = nmap.PortScanner()
nm.scan(sys.argv[1], arguments='-p 21,22,25,80') # List of ports
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

print("Runnning host lookup...\n") # http://ip-api.com/ useful info
for a in range(0, len(hostgrouped), 3):
	result = requests.get('http://ip-api.com/json/'+hostgrouped[a])
	hostgrouped[a+2].append(result.json())

# Formatting output
for a in range(0, len(hostgrouped), 3) :
	print(str(hostgrouped[a]))
	print(str(hostgrouped[a+1]))
	print(str(hostgrouped[a+2]))
	print("----------")
