import { OrgChart } from 'd3-org-chart';
import * as d3 from 'd3';

/**
 * Organizational Chart Initialization
 * This script is bundled via Vite and loaded only on the Struktur Organisasi page.
 */

let chart = null;

window.renderOrgChart = (containerId, data) => {
    try {
        chart = new OrgChart()
            .container(containerId)
            .data(data)
            .nodeWidth(d => 220)
            .nodeHeight(d => 120)
            .childrenMargin(d => 60)
            .compactMarginBetween(d => 40)
            .compactMarginPair(d => 40)
            .neighbourMargin(d => 40)
            .compact(true)
            .nodeContent(function(d, i, arr, state) {
                const color = d.data.level == 1 ? '#206bc4' : '#ffffff';
                const textColor = d.data.level == 1 ? '#ffffff' : '#354052';
                const subColor = d.data.level == 1 ? 'rgba(255,255,255,0.7)' : '#6e7582';
                const avatar = d.data.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(d.data.name) + '&background=random';
                
                return `
                <div style="font-family: 'Inter', sans-serif; height:${d.height}px; width:${d.width}px; background-color:${color}; border: 1px solid #e6e7e9; border-radius: 8px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    <div style="padding: 12px;">
                        <div style="display:flex; align-items:center;">
                            <div style="width:40px; height:40px; border-radius:4px; margin-right:10px; border: 1px solid rgba(0,0,0,0.05); overflow:hidden; background:#f0f2f5;">
                                <img src="${avatar}" style="width:100%; height:100%; object-fit:cover;" />
                            </div>
                            <div style="width: 140px;">
                                <div style="color:${textColor}; font-size:12px; font-weight:bold; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${d.data.name}">${d.data.name}</div>
                                <div style="color:${subColor}; font-size:10px; margin-top:2px;">${d.data.type}</div>
                            </div>
                        </div>
                        <div style="margin-top:12px; display:flex; justify-content:space-between; align-items:flex-end;">
                            <div style="color:${subColor}; font-size:9px; font-weight:600;">CODE: ${d.data.code || '-'}</div>
                            <button onclick="loadUnitDetail('${d.data.encryptedId}', '${d.data.url}')" style="background:#206bc4; color:white; border:none; border-radius:4px; font-size:9px; padding:3px 10px; cursor:pointer; font-weight:600;">Detail</button>
                        </div>
                    </div>
                    <div style="height:4px; width:100%; background:${d.data.level == 1 ? '#1155a3' : '#2d67ff'};"></div>
                </div>
                `;
            })
            .onNodeClick(d => {
                // We keep the detail loading via the button to avoid accidental clicks
                // but we could also trigger it here if desired.
            })
            .linkUpdate(function(d, i, arr) {
                d3.select(this)
                    .attr("stroke", d => d.data._upToTheRootHighlighted ? '#206bc4' : '#e6e7e9')
                    .attr("stroke-width", d => d.data._upToTheRootHighlighted ? 3 : 1.5);
            })
            .render();
        
        window.chart = chart;
    } catch (err) {
        console.error('OrgChart Render Error:', err);
    }
};

// Global safe chart functions for buttons
window.fitChart = () => chart && chart.fit();
window.zoomInChart = () => chart && chart.zoomIn();
window.zoomOutChart = () => chart && chart.zoomOut();
window.expandAllChart = () => chart && chart.expandAll().render();
window.collapseAllChart = () => chart && chart.collapseAll().render();
