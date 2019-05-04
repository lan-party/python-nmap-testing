#!/usr/bin/python
import nmap
import sys
import requests

# Usage:
# python3 001.py


nm = nmap.PortScanner()
nm.scan(sys.argv[1], '20-90') # Shortened port range
nm.command_line()
nm.scaninfo()
nm.all_hosts()
results = nm.csv().splitlines()
hostgrouped = []

for a in range(0, len(results)):
	resultsplit = results[a].split(';')
	if resultsplit[0] != 'host':
		if resultsplit[0] not in hostgrouped: # Supports multiple hosts
			hostgrouped.append(resultsplit[0])
			hostgrouped.append([])
		hostgrouped[hostgrouped.index(resultsplit[0])+1].append(results[a])

# Formatting output
for a in range(0, len(hostgrouped), 2) : 
	print(str(hostgrouped[a]))
	print(str(hostgrouped[a+1]))
	print("----------")
